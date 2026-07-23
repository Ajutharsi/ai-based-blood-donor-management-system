<?php

namespace App\Http\Controllers\Admin;
use App\Models\BloodRequest;
use App\Http\Controllers\Controller;
use App\Models\Donor;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
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

        foreach ($bloodGroups as $bg) {
            $eligible   = Donor::where('blood_group', $bg)->where('is_eligible', true)->count();
            $total      = Donor::where('blood_group', $bg)->count();
            $reqsMonth  = BloodRequest::where('blood_group', $bg)
                            ->whereMonth('created_at', now()->month)
                            ->count();

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

            } catch (\Exception $e) {
                Log::warning('Shortage AI unavailable: ' . $e->getMessage());
                $level = $this->fallbackLevel($eligible);
                $conf  = 0;
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



        $bloodGroups    = ['O+','A+','B+','AB+','O-','A-','B-','AB-'];
$demandForecasts = [];

foreach ($bloodGroups as $bg) {

    // Get request counts for last 4 weeks
    $week1 = BloodRequest::where('blood_group', $bg)
                ->whereBetween('created_at', [
                    now()->subWeeks(4)->startOfWeek(),
                    now()->subWeeks(4)->endOfWeek()
                ])->count();

    $week2 = BloodRequest::where('blood_group', $bg)
                ->whereBetween('created_at', [
                    now()->subWeeks(3)->startOfWeek(),
                    now()->subWeeks(3)->endOfWeek()
                ])->count();

    $week3 = BloodRequest::where('blood_group', $bg)
                ->whereBetween('created_at', [
                    now()->subWeeks(2)->startOfWeek(),
                    now()->subWeeks(2)->endOfWeek()
                ])->count();

    $week4 = BloodRequest::where('blood_group', $bg)
                ->whereBetween('created_at', [
                    now()->subWeeks(1)->startOfWeek(),
                    now()->subWeeks(1)->endOfWeek()
                ])->count();

    // Call Python AI forecast
    try {
        $response = Http::timeout(5)->post(
            config('services.ai.url', 'http://127.0.0.1:8001') . '/forecast-demand',
            [
                'blood_group'     => $bg,
                'requests_week1'  => $week1,
                'requests_week2'  => $week2,
                'requests_week3'  => $week3,
                'requests_week4'  => $week4,
            ]
        );

        if ($response->successful()) {
            $result = $response->json();
            $demandForecasts[$bg] = [
                'blood_group'        => $bg,
                'predicted_requests' => $result['predicted_requests'] ?? 0,
                'demand_level'       => $result['demand_level']       ?? 'medium',
                'trend'              => $result['trend']              ?? 'stable',
                'week_history'       => [$week1, $week2, $week3, $week4],
            ];
        }
    } catch (\Exception $e) {
        // Fallback — simple average
        $avg = ($week1 + $week2 + $week3 + $week4) / 4;
        $demandForecasts[$bg] = [
            'blood_group'        => $bg,
            'predicted_requests' => round($avg),
            'demand_level'       => $avg > 2 ? 'high' : ($avg > 0 ? 'medium' : 'low'),
            'trend'              => 'stable',
            'week_history'       => [$week1, $week2, $week3, $week4],
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

    if (count($allDonors) >= 4) {
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

        return view('admin.dashboard', compact(
            'stats',
            'recent_donors',
            'shortageAlerts',
            'aiUsed',
            'demandForecasts',
            'clusterResult'
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



