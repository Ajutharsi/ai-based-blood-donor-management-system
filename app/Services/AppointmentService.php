<?php

namespace App\Services;

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
            'New appointment booked',
            "{$donor->full_name} booked an appointment for your {$bloodRequest->blood_group} request on " . $appointment->appointment_date->format('d M Y') . '.',
            'appointment_booked', $appointment->id
        );

        return $appointment;
    }

    public function cancel(Appointment $appointment): Appointment
    {
        $appointment->update(['status' => 'cancelled']);

        Notification::notify(
            'hospital', $appointment->hospital_id,
            'Appointment cancelled',
            "{$appointment->donor->full_name} cancelled their appointment scheduled for " . $appointment->appointment_date->format('d M Y') . '.',
            'appointment_cancelled', $appointment->id
        );

        return $appointment->fresh();
    }

    public function approve(Appointment $appointment): Appointment
    {
        $appointment->update(['status' => 'approved']);

        Notification::notify(
            'donor', $appointment->donor_id,
            'Appointment approved',
            "Your appointment at {$appointment->hospital->name} on " . $appointment->appointment_date->format('d M Y') . " at {$appointment->appointment_time} has been approved.",
            'appointment_approved', $appointment->id
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
            'Appointment rejected',
            "Your appointment at {$appointment->hospital->name} on " . $appointment->appointment_date->format('d M Y') . ' could not be accommodated.' . ($reason ? " Reason: {$reason}" : ''),
            'appointment_rejected', $appointment->id
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
            'Appointment rescheduled',
            "Your appointment at {$appointment->hospital->name} has been rescheduled to " . $appointment->fresh()->appointment_date->format('d M Y') . " at {$time}.",
            'appointment_rescheduled', $appointment->id
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
            'Appointment reminder',
            "Reminder: you have a donation appointment at {$appointment->hospital->name} on " . $appointment->appointment_date->format('d M Y') . " at {$appointment->appointment_time}.",
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

        Notification::notifyAllAdmins(
            'Donation completed',
            "{$donor->full_name} completed a donation at {$hospital->name} ({$donor->blood_group}, appointment #{$appointment->id}).",
            'appointment_completed', $appointment->id
        );

        Notification::notify(
            'donor', $appointment->donor_id,
            'Donation completed',
            "Thank you! Your donation at {$hospital->name} on " . $appointment->appointment_date->format('d M Y') . ' has been recorded.',
            'appointment_completed', $appointment->id
        );

        return $appointment->fresh();
    }
}
