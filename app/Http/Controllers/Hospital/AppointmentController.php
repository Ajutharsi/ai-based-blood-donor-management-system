<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(private AppointmentService $appointmentService)
    {
    }

    public function index(Request $request)
    {
        $hospital = auth('hospital')->user();

        $query = Appointment::where('hospital_id', $hospital->id)->with(['donor', 'bloodRequest']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderBy('appointment_date')->orderBy('appointment_time')->get();

        $today = $appointments->filter(fn ($a) => $a->appointment_date->isToday() && in_array($a->status, ['pending', 'approved']))->values();
        $upcoming = $appointments->filter(fn ($a) => $a->appointment_date->isFuture() && in_array($a->status, ['pending', 'approved']))->values();
        $pending = $appointments->where('status', 'pending')->values();
        $history = $appointments->whereIn('status', ['completed', 'rejected', 'cancelled'])->sortByDesc('appointment_date')->values();

        return view('hospital.appointments', compact('hospital', 'appointments', 'today', 'upcoming', 'pending', 'history'));
    }

    private function authorizeOwn(Appointment $appointment): void
    {
        if ($appointment->hospital_id !== auth('hospital')->id()) {
            abort(403);
        }
    }

    public function approve(Appointment $appointment)
    {
        $this->authorizeOwn($appointment);

        if ($appointment->status !== 'pending') {
            abort(403, 'Only pending appointments can be approved.');
        }

        $this->appointmentService->approve($appointment);

        return back()->with('success', 'Appointment approved.');
    }

    public function reject(Request $request, Appointment $appointment)
    {
        $this->authorizeOwn($appointment);

        if ($appointment->status !== 'pending') {
            abort(403, 'Only pending appointments can be rejected.');
        }

        $request->validate(['reason' => 'nullable|string|max:500']);

        $this->appointmentService->reject($appointment, $request->reason);

        return back()->with('success', 'Appointment rejected.');
    }

    public function reschedule(Request $request, Appointment $appointment)
    {
        $this->authorizeOwn($appointment);

        if (!in_array($appointment->status, ['pending', 'approved'], true)) {
            abort(403, 'Only pending or approved appointments can be rescheduled.');
        }

        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
        ]);

        $this->appointmentService->reschedule($appointment, $request->appointment_date, $request->appointment_time);

        return back()->with('success', 'Appointment rescheduled.');
    }

    public function complete(Appointment $appointment)
    {
        $this->authorizeOwn($appointment);

        if ($appointment->status !== 'approved') {
            abort(403, 'Only approved appointments can be marked completed.');
        }

        $this->appointmentService->complete($appointment);

        return back()->with('success', 'Appointment marked completed and donation recorded.');
    }
}
