<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Hospital;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    private const BLOOD_GROUPS = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];

    // Read-only, cross-hospital view -- admins can see every appointment
    // but never approve/reject/complete them (only the owning hospital can).
    public function index(Request $request)
    {
        $query = Appointment::with(['hospital', 'donor', 'bloodRequest']);

        if ($request->filled('hospital_id')) {
            $query->where('hospital_id', $request->hospital_id);
        }
        if ($request->filled('blood_group')) {
            $query->whereHas('bloodRequest', fn ($q) => $q->where('blood_group', $request->blood_group));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->latest('appointment_date')->paginate(15)->withQueryString();

        $stats = [
            'total'     => Appointment::count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'pending'   => Appointment::where('status', 'pending')->count(),
            'approved'  => Appointment::where('status', 'approved')->count(),
            'rejected'  => Appointment::where('status', 'rejected')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
        ];

        // Last 6 months of appointment volume, oldest first, for the
        // admin dashboard/report monthly chart.
        $monthlyChart = collect(range(5, 0))->map(function ($monthsAgo) {
            $month = now()->subMonths($monthsAgo);
            return [
                'label' => $month->format('M Y'),
                'total' => Appointment::whereYear('appointment_date', $month->year)
                    ->whereMonth('appointment_date', $month->month)
                    ->count(),
                'completed' => Appointment::whereYear('appointment_date', $month->year)
                    ->whereMonth('appointment_date', $month->month)
                    ->where('status', 'completed')
                    ->count(),
            ];
        })->values();

        $byHospital = Appointment::with('hospital')->get()
            ->groupBy('hospital_id')
            ->map(fn ($group) => [
                'hospital' => $group->first()->hospital?->name ?? 'Unknown',
                'total'     => $group->count(),
                'completed' => $group->where('status', 'completed')->count(),
            ])
            ->sortByDesc('total')
            ->values();

        $byBloodGroup = Appointment::with('bloodRequest')->get()
            ->groupBy(fn ($a) => $a->bloodRequest?->blood_group ?? 'Unknown')
            ->map(fn ($group, $bg) => [
                'blood_group' => $bg,
                'total'       => $group->count(),
                'completed'   => $group->where('status', 'completed')->count(),
            ])
            ->sortByDesc('total')
            ->values();

        $hospitals = Hospital::orderBy('name')->get(['id', 'name']);

        return view('admin.appointments.index', compact(
            'appointments', 'stats', 'monthlyChart', 'byHospital', 'byBloodGroup', 'hospitals'
        ));
    }
}
