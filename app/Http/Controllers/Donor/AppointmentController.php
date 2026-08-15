<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\BloodRequest;
use App\Models\DonorResponse;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(private AppointmentService $appointmentService)
    {
    }

    public function index()
    {
        $donor = auth('donor')->user();

        $appointments = $donor->appointments()->with(['bloodRequest', 'hospital'])->get();
        $upcoming = $appointments->whereIn('status', ['pending', 'approved'])
            ->sortBy('appointment_date')->values();
        $history = $appointments->whereIn('status', ['completed', 'rejected', 'cancelled'])
            ->sortByDesc('appointment_date')->values();

        // Only requests this donor has confirmed "available" for, and
        // doesn't already have a booked (non-cancelled/rejected)
        // appointment against, are bookable -- matches the requirement
        // that only a donor who responded Available may book.
        $alreadyBooked = $appointments->whereNotIn('status', ['cancelled', 'rejected'])->pluck('blood_request_id');
        $bookableRequestIds = DonorResponse::where('donor_id', $donor->id)
            ->where('status', 'available')
            ->pluck('blood_request_id')
            ->diff($alreadyBooked);
        $bookableRequests = BloodRequest::whereIn('id', $bookableRequestIds)
            ->where('status', 'pending')
            ->with('hospital')
            ->get();

        return view('donor.appointments', compact('donor', 'upcoming', 'history', 'bookableRequests'));
    }

    public function store(Request $request, BloodRequest $bloodRequest)
    {
        $donor = auth('donor')->user();

        $isAvailable = DonorResponse::where('donor_id', $donor->id)
            ->where('blood_request_id', $bloodRequest->id)
            ->where('status', 'available')
            ->exists();

        if (!$isAvailable) {
            abort(403, 'Only donors who responded Available for this request may book an appointment.');
        }

        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'notes'            => 'nullable|string|max:500',
        ]);

        $this->appointmentService->book(
            $bloodRequest, $donor, $request->appointment_date, $request->appointment_time, $request->notes
        );

        return back()->with('success', 'Appointment booked. The hospital will confirm it shortly.');
    }

    public function cancel(Appointment $appointment)
    {
        $donor = auth('donor')->user();

        if ($appointment->donor_id !== $donor->id) {
            abort(403);
        }
        if (!$appointment->isPending() && $appointment->status !== 'approved') {
            abort(403, 'Only pending or approved appointments can be cancelled.');
        }

        $this->appointmentService->cancel($appointment);

        return back()->with('success', 'Appointment cancelled.');
    }
}
