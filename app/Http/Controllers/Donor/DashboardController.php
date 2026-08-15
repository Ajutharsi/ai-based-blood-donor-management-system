<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\AiPrediction;
use App\Services\AiEligibilityService;

class DashboardController extends Controller
{
    public function index()
    {
        $donor = auth('donor')->user();
        $modelMetrics = (new AiEligibilityService())->getModelInfo();

        $notifications = $donor->notifications()->take(8)->get();
        $unreadNotifications = $donor->unreadNotificationsCount();

        $appointments = $donor->appointments()->with(['bloodRequest', 'hospital'])->get();
        $upcomingAppointment = $appointments->whereIn('status', ['pending', 'approved'])->sortBy('appointment_date')->first();
        $appointmentHistory = $appointments->whereIn('status', ['completed', 'rejected', 'cancelled'])->sortByDesc('appointment_date')->take(5)->values();

        // ── ADVANCED ANALYTICS ──
        // Personal donation history: monthly frequency over the last year.
        $donationHistoryChart = collect(range(11, 0))->map(function ($monthsAgo) use ($donor) {
            $month = now()->subMonths($monthsAgo);
            return [
                'label' => $month->format('M Y'),
                'count' => $donor->donations()
                    ->whereYear('donation_date', $month->year)
                    ->whereMonth('donation_date', $month->month)
                    ->count(),
            ];
        })->values();

        // Donation timeline: the same donations, in chronological display
        // order, for a visual timeline component rather than an aggregate.
        $donationTimeline = $donor->donations()->orderByDesc('donation_date')->get();

        // AI prediction history: every AI call made about this donor
        // (eligibility/response/anomaly), oldest first, for a confidence
        // trend line.
        $aiPredictionHistory = AiPrediction::where('donor_id', $donor->id)
            ->orderBy('created_at')
            ->get()
            ->map(fn ($p) => [
                'label'      => $p->created_at->format('d M Y'),
                'type'       => ucfirst($p->prediction_type),
                'confidence' => $p->confidence,
            ])
            ->values();

        return view('donor.donor_dashboard', compact(
            'donor', 'modelMetrics', 'notifications', 'unreadNotifications',
            'upcomingAppointment', 'appointmentHistory',
            'donationHistoryChart', 'donationTimeline', 'aiPredictionHistory'
        ));
    }
}