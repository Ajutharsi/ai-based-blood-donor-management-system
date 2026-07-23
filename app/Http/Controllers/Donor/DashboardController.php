<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $donor = auth('donor')->user();
        return view('donor.donor_dashboard', compact('donor'));
    }
}