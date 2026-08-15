<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Appointment;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\Notification;

/**
 * Centralises every appointment status transition so that notifications
 * and (on completion) the donation/inventory side effects always happen
 * together with the status change -- no controller ever updates an
 * appointment's status directly.
 */
class AppointmentService
{
    public function __construct(private BloodInventoryService $inventoryService)
    {
    }

    // Booking is an upsert keyed on (donor_id, blood_request_id) -- the
    // unique constraint on that pair means a donor rebooking the same
    // request updates their existing appointment instead of erroring.
    public function book(BloodRequest $bloodRequest, Donor $donor, string $date, string $time, ?string $notes = null): Appointment
    {
        $appointment = Appointment::updateOrCreate(
            ['donor_id' => $donor->id, 'blood_request_id' => $bloodRequest->id],
            [
                'hospital_id'      => $bloodRequest->hospital_id,
                'appointment_date' => $date,
                'appointment_time' => $time,
                'status'           => 'pending',
                'notes'            => $notes,
            ]
        );

        Notification::notify(
            'hospital', $bloodRequest->hospital_id,
            __('New appointment booked'),
            __(':donor booked an appointment for your :group request on :date.', [
                'donor' => $donor->full_name,
                'group' => $bloodRequest->blood_group,
                'date'  => $appointment->appointment_date->format('d M Y'),
            ]),
            'appointment_booked', $appointment->id
        );

        ActivityLog::log(
            'appointment', 'appointment_booked',
            __(':donor booked an appointment for the :group request on :date.', [
                'donor' => $donor->full_name, 'group' => $bloodRequest->blood_group, 'date' => $appointment->appointment_date->format('d M Y'),
            ]),
            'Appointment', $appointment->id
        );

        return $appointment;
    }

    public function cancel(Appointment $appointment): Appointment
    {
        $appointment->update(['status' => 'cancelled']);

        Notification::notify(
            'hospital', $appointment->hospital_id,
            __('Appointment cancelled'),
            __(':donor cancelled their appointment scheduled for :date.', [
                'donor' => $appointment->donor->full_name,
                'date'  => $appointment->appointment_date->format('d M Y'),
            ]),
            'appointment_cancelled', $appointment->id
        );

        ActivityLog::log(
            'appointment', 'appointment_cancelled',
            __(':donor cancelled their appointment scheduled for :date.', [
                'donor' => $appointment->donor->full_name, 'date' => $appointment->appointment_date->format('d M Y'),
            ]),
            'Appointment', $appointment->id
        );

        return $appointment->fresh();
    }

    public function approve(Appointment $appointment): Appointment
    {
        $appointment->update(['status' => 'approved']);

        Notification::notify(
            'donor', $appointment->donor_id,
            __('Appointment approved'),
            __('Your appointment at :hospital on :date at :time has been approved.', [
                'hospital' => $appointment->hospital->name,
                'date'     => $appointment->appointment_date->format('d M Y'),
                'time'     => $appointment->appointment_time,
            ]),
            'appointment_approved', $appointment->id
        );

        ActivityLog::log(
            'appointment', 'appointment_approved',
            __('Appointment for :donor at :hospital on :date was approved.', [
                'donor' => $appointment->donor->full_name, 'hospital' => $appointment->hospital->name, 'date' => $appointment->appointment_date->format('d M Y'),
            ]),
            'Appointment', $appointment->id
        );

        return $appointment->fresh();
    }

    public function reject(Appointment $appointment, ?string $reason = null): Appointment
    {
        $appointment->update([
            'status' => 'rejected',
            'notes'  => $reason ?: $appointment->notes,
        ]);

        Notification::notify(
            'donor', $appointment->donor_id,
            __('Appointment rejected'),
            $reason
                ? __('Your appointment at :hospital on :date could not be accommodated. Reason: :reason', [
                    'hospital' => $appointment->hospital->name,
                    'date'     => $appointment->appointment_date->format('d M Y'),
                    'reason'   => $reason,
                ])
                : __('Your appointment at :hospital on :date could not be accommodated.', [
                    'hospital' => $appointment->hospital->name,
                    'date'     => $appointment->appointment_date->format('d M Y'),
                ]),
            'appointment_rejected', $appointment->id
        );

        ActivityLog::log(
            'appointment', 'appointment_rejected',
            __('Appointment for :donor at :hospital on :date was rejected.', [
                'donor' => $appointment->donor->full_name, 'hospital' => $appointment->hospital->name, 'date' => $appointment->appointment_date->format('d M Y'),
            ]),
            'Appointment', $appointment->id,
            ['reason' => $reason]
        );

        return $appointment->fresh();
    }

    public function reschedule(Appointment $appointment, string $date, string $time): Appointment
    {
        $appointment->update([
            'appointment_date' => $date,
            'appointment_time' => $time,
        ]);

        Notification::notify(
            'donor', $appointment->donor_id,
            __('Appointment rescheduled'),
            __('Your appointment at :hospital has been rescheduled to :date at :time.', [
                'hospital' => $appointment->hospital->name,
                'date'     => $appointment->fresh()->appointment_date->format('d M Y'),
                'time'     => $time,
            ]),
            'appointment_rescheduled', $appointment->id
        );

        ActivityLog::log(
            'appointment', 'appointment_rescheduled',
            __('Appointment for :donor at :hospital rescheduled to :date at :time.', [
                'donor' => $appointment->donor->full_name, 'hospital' => $appointment->hospital->name,
                'date'  => $appointment->fresh()->appointment_date->format('d M Y'), 'time' => $time,
            ]),
            'Appointment', $appointment->id
        );

        return $appointment->fresh();
    }

    // A day-before reminder for any appointment still pending/approved --
    // intended to be called from a scheduled job, but exposed here as a
    // plain method so it's directly testable without wiring a scheduler.
    public function sendReminder(Appointment $appointment): void
    {
        if (!in_array($appointment->status, ['pending', 'approved'], true)) {
            return;
        }

        Notification::notify(
            'donor', $appointment->donor_id,
            __('Appointment reminder'),
            __('Reminder: you have a donation appointment at :hospital on :date at :time.', [
                'hospital' => $appointment->hospital->name,
                'date'     => $appointment->appointment_date->format('d M Y'),
                'time'     => $appointment->appointment_time,
            ]),
            'appointment_reminder', $appointment->id
        );
    }

    // Marking an appointment completed is the one transition with real
    // side effects: it records the actual donation, updates the donor's
    // stats, and credits the hospital's blood inventory (with its own
    // audit log entry via BloodInventoryService) -- all in one place so
    // none of these can happen out of sync with each other.
    public function complete(Appointment $appointment): Appointment
    {
        $appointment->update(['status' => 'completed']);

        $donor = $appointment->donor;
        $hospital = $appointment->hospital;

        $donation = $donor->donations()->create([
            'donation_date'   => $appointment->appointment_date,
            'blood_group'     => $donor->blood_group,
            'donation_center' => $hospital->name,
            'units'           => 1,
            'notes'           => "Recorded via appointment #{$appointment->id}",
        ]);

        $donor->increment('total_donations');
        if (!$donor->last_donation_date || $appointment->appointment_date->gt($donor->last_donation_date)) {
            $donor->update(['last_donation_date' => $appointment->appointment_date]);
        }

        $inventory = $this->inventoryService->findOrCreate($hospital->id, $donor->blood_group);
        $this->inventoryService->addUnits($inventory, 1, "Donation completed via appointment #{$appointment->id}");

        ActivityLog::log(
            'appointment', 'appointment_completed',
            __('Appointment for :donor at :hospital marked completed.', ['donor' => $donor->full_name, 'hospital' => $hospital->name]),
            'Appointment', $appointment->id
        );

        ActivityLog::log(
            'donation', 'donation_recorded',
            __(':donor donated :group at :hospital (via appointment #:id).', [
                'donor' => $donor->full_name, 'group' => $donor->blood_group, 'hospital' => $hospital->name, 'id' => $appointment->id,
            ]),
            'Donation', $donation->id
        );

        Notification::notifyAllAdmins(
            __('Donation completed'),
            __(':donor completed a donation at :hospital (:group, appointment #:id).', [
                'donor'    => $donor->full_name,
                'hospital' => $hospital->name,
                'group'    => $donor->blood_group,
                'id'       => $appointment->id,
            ]),
            'appointment_completed', $appointment->id
        );

        Notification::notify(
            'donor', $appointment->donor_id,
            __('Donation completed'),
            __('Thank you! Your donation at :hospital on :date has been recorded.', [
                'hospital' => $hospital->name,
                'date'     => $appointment->appointment_date->format('d M Y'),
            ]),
            'appointment_completed', $appointment->id
        );

        return $appointment->fresh();
    }
}
