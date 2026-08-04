<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
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

        return view('donor.donor_dashboard', compact(
            'donor', 'modelMetrics', 'notifications', 'unreadNotifications',
            'upcomingAppointment', 'appointmentHistory'
        ));
    }
}