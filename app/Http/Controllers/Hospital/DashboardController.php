<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Services\DistanceService;

class DashboardController extends Controller
{
    public function __construct(private DistanceService $distanceService)
    {
    }

    public function index()
    {
        $hospital = auth('hospital')->user();

        $stats = [
            'total_requests'    => BloodRequest::where('hospital_id', $hospital->id)->count(),
            'pending'           => BloodRequest::where('hospital_id', $hospital->id)->where('status', 'pending')->count(),
            'fulfilled'         => BloodRequest::where('hospital_id', $hospital->id)->where('status', 'fulfilled')->count(),
            'this_month'        => BloodRequest::where('hospital_id', $hospital->id)->whereMonth('created_at', now()->month)->count(),
        ];

        $recent_requests = BloodRequest::where('hospital_id', $hospital->id)
                            ->latest()->take(5)->get();

        [$nearbyDonors, $averageDonorDistance, $closestDonor] = $this->nearbyDonorStats($hospital);

        $notifications = $hospital->notifications()->take(8)->get();
        $unreadNotifications = $hospital->unreadNotificationsCount();

        $appointmentsQuery = Appointment::where('hospital_id', $hospital->id)->with(['donor', 'bloodRequest']);
        $todayAppointments = (clone $appointmentsQuery)->whereDate('appointment_date', now()->toDateString())
            ->whereIn('status', ['pending', 'approved'])->orderBy('appointment_time')->get();
        $upcomingAppointments = (clone $appointmentsQuery)->where('appointment_date', '>', now()->toDateString())
            ->whereIn('status', ['pending', 'approved'])->orderBy('appointment_date')->take(5)->get();
        $pendingAppointmentsCount = (clone $appointmentsQuery)->where('status', 'pending')->count();

        return view('hospital.hospital_dashboard', compact(
            'hospital', 'stats', 'recent_requests', 'notifications', 'unreadNotifications',
            'nearbyDonors', 'averageDonorDistance', 'closestDonor',
            'todayAppointments', 'upcomingAppointments', 'pendingAppointmentsCount'
        ));
    }

    // Eligible donors ranked by real distance from this hospital. Requires
    // both the hospital and a donor to have set a location -- if the
    // hospital hasn't, there's nothing to rank against, so this returns
    // empty rather than falling back to district (that's what the
    // existing matching/find-donors flows already do).
    private function nearbyDonorStats($hospital): array
    {
        if (!$hospital->hasLocation()) {
            return [collect(), null, null];
        }

        $eligibleWithLocation = Donor::where('is_eligible', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $ranked = $eligibleWithLocation
            ->map(function (Donor $donor) use ($hospital) {
                $donor->distance_km = $this->distanceService->calculate(
                    $hospital->latitude, $hospital->longitude, $donor->latitude, $donor->longitude
                );
                $donor->travel_category = $this->distanceService->travelCategory($donor->distance_km);

                return $donor;
            })
            ->sortBy('distance_km')
            ->values();

        if ($ranked->isEmpty()) {
            return [collect(), null, null];
        }

        $averageDistance = round($ranked->avg('distance_km'), 1);
        $closest = $ranked->first();

        return [$ranked->take(5), $averageDistance, $closest];
    }
}
