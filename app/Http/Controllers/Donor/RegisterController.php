<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Services\AiEligibilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('donor.donor_registration_page');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'email'         => 'required|email|unique:donors,email',
            'password'      => 'required|min:8|confirmed',
            'date_of_birth' => 'nullable|date',
            'gender'        => 'nullable|in:Male,Female,Other',
            'nic'           => 'nullable|string|unique:donors,nic',
            'blood_group'   => 'nullable|string',
            'weight_kg'     => 'nullable|numeric|min:30|max:200',
            'hemoglobin'    => 'nullable|numeric|min:5|max:25',
            'city'          => 'nullable|string|max:100',
            'district'      => 'nullable|string|max:100',
        ]);

        // Calculate age
        $age = null;
        if ($request->date_of_birth) {
            $age = \Carbon\Carbon::parse($request->date_of_birth)->age;
        }

        // ── Call Python AI ──
        $ai = new AiEligibilityService();
        $result = $ai->predict([
            'age'             => $age ?? 0,
            'weight_kg'       => $request->weight_kg ?? 0,
            'hemoglobin'      => $request->hemoglobin ?? 0,
            'total_donations' => $request->total_donations ?? 0,
            'blood_group'     => $request->blood_group ?? 'O+',
            'gender'          => $request->gender ?? 'Male',
        ]);

  

// ── Call Python AI — Response Probability ──
$responseResult = $ai->predictResponse([
    'age'             => $age ?? 0,
    'weight_kg'       => $request->weight_kg  ?? 0,
    'hemoglobin'      => $request->hemoglobin ?? 0,
    'total_donations' => $request->total_donations ?? 0,
    'gender'          => $request->gender ?? 'Male',
]);

// ── Call Python AI — Anomaly Detection ──
$anomalyResult = $ai->detectAnomaly([
    'age'             => $age ?? 0,
    'weight_kg'       => $request->weight_kg  ?? 0,
    'hemoglobin'      => $request->hemoglobin ?? 0,
    'total_donations' => $request->total_donations ?? 0,
]);



        // Create donor
        $donor = Donor::create([
            'first_name'      => $request->first_name,
            'last_name'       => $request->last_name,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'phone'           => $request->phone,
            'date_of_birth'   => $request->date_of_birth,
            'age'             => $age,
            'gender'          => $request->gender,
            'nic'             => $request->nic,
            'blood_group'     => $request->blood_group,
            'weight_kg'       => $request->weight_kg,
            'hemoglobin'      => $request->hemoglobin,
            'total_donations' => $request->total_donations ?? 0,
            'last_donation_date' => $request->last_donation_date,
            'city'            => $request->city,
            'district'        => $request->district,
            'donation_center' => $request->donation_center,
            'medical_notes'   => $request->medical_notes,
            'is_eligible'     => $result['eligible'],
            'ai_confidence'   => $result['confidence'],
             'is_eligible'          => $result['eligible'],
           'response_probability' => $responseResult['response_probability'] ?? 50,
           'response_level'       => $responseResult['level']                ?? 'medium',
           'is_anomaly'           => $anomalyResult['is_anomaly']            ?? false,
            'anomaly_score'        => $anomalyResult['anomaly_score']         ?? 0,
        ]);

        auth('donor')->login($donor);

        return redirect()->route('donor.dashboard')
                         ->with('success', 'Welcome to LifeLink, ' . $donor->first_name . '! AI eligibility check complete.');
    }
}