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

        return view('donor.donor_dashboard', compact('donor', 'modelMetrics'));
    }
}