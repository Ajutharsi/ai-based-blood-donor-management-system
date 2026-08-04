<?php

namespace App\Http\Controllers\Admin;
use App\Models\BloodRequest;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\BloodInventory;
use App\Models\Donor;
use App\Models\Hospital;
use App\Services\DistanceService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct(private DistanceService $distanceService)
    {
    }

      public function index()
    {
        $stats = [
            'total_donors'    => Donor::count(),
            'eligible_donors' => Donor::where('is_eligible', true)->count(),
            'not_eligible'    => Donor::where('is_eligible', false)->count(),
            'new_this_month'  => Donor::whereMonth('created_at', now()->month)->count(),
        ];

        $recent_donors = Donor::latest()->take(5)->get();

        // ── KNN BLOOD SHORTAGE ANALYSIS ──
        $bloodGroups    = ['O+','A+','B+','AB+','O-','A-','B-','AB-'];
        $shortageAlerts = [];
        $aiUsed         = false;
        $aiAvailable    = true; // short-circuit further AI calls this request once it's confirmed unreachable

        foreach ($bloodGroups as $bg) {
            $eligible   = Donor::where('blood_group', $bg)->where('is_eligible', true)->count();
            $total      = Donor::where('blood_group', $bg)->count();
            $reqsMonth  = BloodRequest::where('blood_group', $bg)
                            ->whereMonth('created_at', now()->month)
                            ->count();

            if (!$aiAvailable) {
                $level = $this->fallbackLevel($eligible);
                $conf  = 0;
            } else {
                // Call Python KNN model
                try {
                    $response = Http::timeout(5)->post(
                        config('services.ai.url', 'http://127.0.0.1:8001') . '/predict-shortage',
                        [
                            'blood_group'         => $bg,
                            'eligible_count'      => $eligible,
                            'total_donors'        => $total,
                            'requests_last_month' => $reqsMonth,
                        ]
                    );

                    if ($response->successful()) {
                        $result = $response->json();
                        $level  = $result['level']      ?? 'sufficient';
                        $conf   = $result['confidence'] ?? 0;
                        $aiUsed = true;
                    } else {
                        // Fallback
                        $level = $this->fallbackLevel($eligible);
                        $conf  = 0;
                    }

                } catch (ConnectionException $e) {
                    Log::warning('Shortage AI unavailable: ' . $e->getMessage());
                    $aiAvailable = false;
                    $level = $this->fallbackLevel($eligible);
                    $conf  = 0;
                } catch (\Exception $e) {
                    Log::warning('Shortage AI unavailable: ' . $e->getMessage());
                    $level = $this->fallbackLevel($eligible);
                    $conf  = 0;
                }
            }

            // Only add to alerts if not sufficient
            if ($level !== 'sufficient') {
                $shortageAlerts[] = [
                    'blood_group'    => $bg,
                    'eligible_count' => $eligible,
                    'total_donors'   => $total,
                    'level'          => $level,
                    'confidence'     => $conf,
                    'requests_month' => $reqsMonth,
                    'message'        => $eligible === 0
                        ? 'No eligible donors available'
                        : 'Only ' . $eligible . ' eligible donor' . ($eligible > 1 ? 's' : '') . ' available',
                ];
            }
        }



        $bloodGroups     = ['O+','A+','B+','AB+','O-','A-','B-','AB-'];
$demandForecasts = [];
$forecastHistoryWeeks = 8; // real history, not a fixed too-small 4-point window

foreach ($bloodGroups as $bg) {

    // Real weekly request counts, oldest first — as many weeks as
    // forecastHistoryWeeks specifies, not hardcoded to exactly 4.
    $weeklyCounts = [];
    for ($w = $forecastHistoryWeeks; $w >= 1; $w--) {
        $weeklyCounts[] = BloodRequest::where('blood_group', $bg)
            ->whereBetween('created_at', [
                now()->subWeeks($w)->startOfWeek(),
                now()->subWeeks($w)->endOfWeek(),
            ])->count();
    }

    // Real signal: donors currently eligible for this blood group.
    $eligibleForForecast = Donor::where('blood_group', $bg)->where('is_eligible', true)->count();

    // Real geographic breakdown: which districts actually submitted requests
    // for this blood group in the same history window, via the hospitals
    // that raised them. This is what "separate the requirement
    // geographically" (proposal 3.3) means here -- rather than exploding the
    // dashboard into 8 blood groups x every district as separate forecasts,
    // each blood-group card surfaces its real top districts by demand.
    $districtBreakdown = BloodRequest::where('blood_requests.blood_group', $bg)
        ->join('hospitals', 'hospitals.id', '=', 'blood_requests.hospital_id')
        ->whereNotNull('hospitals.district')
        ->where('blood_requests.created_at', '>=', now()->subWeeks($forecastHistoryWeeks)->startOfWeek())
        ->selectRaw('hospitals.district as district, count(*) as request_count')
        ->groupBy('hospitals.district')
        ->orderByDesc('request_count')
        ->limit(3)
        ->get()
        ->map(fn ($row) => ['district' => $row->district, 'request_count' => (int) $row->request_count])
        ->all();

    if (!$aiAvailable) {
        $avg = count($weeklyCounts) > 0 ? array_sum($weeklyCounts) / count($weeklyCounts) : 0;
        $demandForecasts[$bg] = [
            'blood_group'        => $bg,
            'predicted_requests' => round($avg),
            'demand_level'       => $avg > 2 ? 'high' : ($avg > 0 ? 'medium' : 'low'),
            'trend'              => 'stable',
            'model'              => 'moving average (AI unavailable)',
            'week_history'       => $weeklyCounts,
            'district_breakdown' => $districtBreakdown,
        ];
        continue;
    }

    // Call Python AI forecast
    try {
        $response = Http::timeout(5)->post(
            config('services.ai.url', 'http://127.0.0.1:8001') . '/forecast-demand',
            [
                'blood_group'     => $bg,
                'weekly_counts'   => $weeklyCounts,
                'eligible_donors' => $eligibleForForecast,
            ]
        );

        if ($response->successful()) {
            $result = $response->json();
            $demandForecasts[$bg] = [
                'blood_group'        => $bg,
                'predicted_requests' => $result['predicted_requests'] ?? 0,
                'demand_level'       => $result['demand_level']       ?? 'medium',
                'trend'              => $result['trend']              ?? 'stable',
                'model'              => $result['model']              ?? 'unknown',
                'week_history'       => $weeklyCounts,
                'district_breakdown' => $districtBreakdown,
            ];
        }
    } catch (ConnectionException $e) {
        $aiAvailable = false;
        $avg = count($weeklyCounts) > 0 ? array_sum($weeklyCounts) / count($weeklyCounts) : 0;
        $demandForecasts[$bg] = [
            'blood_group'        => $bg,
            'predicted_requests' => round($avg),
            'demand_level'       => $avg > 2 ? 'high' : ($avg > 0 ? 'medium' : 'low'),
            'trend'              => 'stable',
            'model'              => 'moving average (AI unavailable)',
            'week_history'       => $weeklyCounts,
            'district_breakdown' => $districtBreakdown,
        ];
    } catch (\Exception $e) {
        // Fallback — simple average
        $avg = count($weeklyCounts) > 0 ? array_sum($weeklyCounts) / count($weeklyCounts) : 0;
        $demandForecasts[$bg] = [
            'blood_group'        => $bg,
            'predicted_requests' => round($avg),
            'demand_level'       => $avg > 2 ? 'high' : ($avg > 0 ? 'medium' : 'low'),
            'trend'              => 'stable',
            'model'              => 'moving average (AI unavailable)',
            'week_history'       => $weeklyCounts,
            'district_breakdown' => $districtBreakdown,
        ];
    }
}

// ── DONOR CLUSTER ANALYSIS (K-Means) ──
$clusterResult = ['clusters' => [], 'status' => 'error'];

try {
    // Send all donors to Python for clustering
    $allDonors = Donor::select(
        'age', 'weight_kg', 'hemoglobin',
        'total_donations', 'is_eligible'
    )->get()->toArray();

    if ($aiAvailable && count($allDonors) >= 4) {
        $response = Http::timeout(15)->post(
            config('services.ai.url', 'http://127.0.0.1:8001') . '/cluster-donors',
            ['donors' => $allDonors]
        );

        if ($response->successful()) {
            $clusterResult = $response->json();
        }
    }
} catch (\Exception $e) {
    Log::warning('Cluster AI error: ' . $e->getMessage());
}

// ── REAL ELIGIBILITY MODEL METRICS (replaces a previously hardcoded
// "94.99% / Logistic Regression" figure with the actual held-out-test
// accuracy/precision/recall/F1 for whichever model is currently deployed,
// as reported by save_model.py's last training run) ──
$modelMetrics = null;

if ($aiAvailable) {
    try {
        $response = Http::timeout(5)->get(
            config('services.ai.url', 'http://127.0.0.1:8001') . '/model-info'
        );

        if ($response->successful() && ($response->json('status') === 'success')) {
            $modelMetrics = $response->json();
        }
    } catch (\Exception $e) {
        Log::warning('Model metrics unavailable: ' . $e->getMessage());
    }
}

        // ── BLOOD INVENTORY SUMMARY (real stock, not AI-predicted) ──
        // Distinct from $shortageAlerts above: that section measures donor
        // *supply* (how many eligible donors exist); this measures actual
        // *recorded stock* hospitals have on hand right now.
        $allInventory = BloodInventory::with('hospital')->get();
        $inventoryTotalUnits = (int) $allInventory->sum('available_units');
        $inventoryAlerts = $allInventory->filter(fn ($i) => $i->status() !== 'sufficient')
            ->sortBy(fn ($i) => $i->status() === 'critical' ? 0 : 1)
            ->values();
        $inventoryLowStockHospitalCount = $inventoryAlerts->pluck('hospital_id')->unique()->count();
        $inventoryCriticalGroups = $inventoryAlerts->filter(fn ($i) => $i->status() === 'critical')
            ->pluck('blood_group')->unique()->values();

        // ── DONOR GEOGRAPHIC DISTRIBUTION ──
        // Aggregate only -- no individual donor's exact coordinates are
        // exposed here (that stays hospital-only, on the Matched Donors
        // page). "Average donor distance to hospitals" = for each hospital
        // with a location set, the average distance to every located
        // eligible donor; then averaged again across hospitals.
        $donorsWithLocation = Donor::whereNotNull('latitude')->whereNotNull('longitude')->get();
        $hospitalsWithLocation = Hospital::whereNotNull('latitude')->whereNotNull('longitude')->get();

        $geoDistribution = [
            'donors_with_location'    => $donorsWithLocation->count(),
            'donors_without_location' => Donor::whereNull('latitude')->orWhereNull('longitude')->count(),
            'hospitals_with_location' => $hospitalsWithLocation->count(),
            'by_district'             => Donor::whereNotNull('latitude')
                ->select('district')
                ->selectRaw('count(*) as total')
                ->groupBy('district')
                ->orderByDesc('total')
                ->limit(5)
                ->pluck('total', 'district'),
        ];

        $averageDonorDistanceToHospitals = null;
        if ($hospitalsWithLocation->isNotEmpty() && $donorsWithLocation->isNotEmpty()) {
            $eligibleDonorsWithLocation = $donorsWithLocation->where('is_eligible', true);

            $perHospitalAverages = $hospitalsWithLocation->map(function (Hospital $hospital) use ($eligibleDonorsWithLocation) {
                if ($eligibleDonorsWithLocation->isEmpty()) {
                    return null;
                }

                $distances = $eligibleDonorsWithLocation->map(fn (Donor $donor) => $this->distanceService->calculate(
                    $hospital->latitude, $hospital->longitude, $donor->latitude, $donor->longitude
                ));

                return $distances->avg();
            })->filter(fn ($avg) => $avg !== null);

            if ($perHospitalAverages->isNotEmpty()) {
                $averageDonorDistanceToHospitals = round($perHospitalAverages->avg(), 1);
            }
        }

        // Hospital map markers only -- individual donor coordinates are
        // never sent to the admin dashboard, even anonymised, since admins
        // are limited to aggregated donor statistics (see $geoDistribution
        // above), not per-donor location data.
        $hospitalMapMarkers = $hospitalsWithLocation
            ->map(fn (Hospital $h) => ['lat' => $h->latitude, 'lng' => $h->longitude, 'name' => $h->name])
            ->values();

        // ── APPOINTMENT STATISTICS (read-only summary; full breakdown lives
        // on the dedicated Appointments page) ──
        $appointmentStats = [
            'total'     => Appointment::count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'pending'   => Appointment::where('status', 'pending')->count(),
            'cancelled' => Appointment::whereIn('status', ['cancelled', 'rejected'])->count(),
        ];
        $appointmentMonthlyChart = collect(range(5, 0))->map(function ($monthsAgo) {
            $month = now()->subMonths($monthsAgo);
            return [
                'label' => $month->format('M Y'),
                'total' => Appointment::whereYear('appointment_date', $month->year)
                    ->whereMonth('appointment_date', $month->month)->count(),
            ];
        })->values();

        $admin = auth('admin')->user();
        $notifications = $admin->notifications()->take(8)->get();
        $unreadNotifications = $admin->unreadNotificationsCount();

        return view('admin.dashboard', compact(
            'stats',
            'recent_donors',
            'shortageAlerts',
            'aiUsed',
            'demandForecasts',
            'clusterResult',
            'modelMetrics',
            'notifications',
            'unreadNotifications',
            'inventoryTotalUnits',
            'inventoryAlerts',
            'inventoryLowStockHospitalCount',
            'inventoryCriticalGroups',
            'geoDistribution',
            'averageDonorDistanceToHospitals',
            'hospitalMapMarkers',
            'appointmentStats',
            'appointmentMonthlyChart'
        ));
    }



    // Fallback if Python AI is down
    private function fallbackLevel(int $eligible): string
    {
        if ($eligible === 0)  return 'critical';
        if ($eligible < 5)   return 'warning';
        return 'sufficient';
    }
}



