<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;

class DashboardController extends Controller
{
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

        return view('hospital.hospital_dashboard', compact('hospital', 'stats', 'recent_requests'));
    }
}