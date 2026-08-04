<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Donor\RegisterDonorRequest;
use App\Models\AiPrediction;
use App\Models\Donor;
use App\Models\Notification;
use App\Services\AiEligibilityService;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('donor.donor_registration_page');
    }

    public function register(RegisterDonorRequest $request)
    {
        // Calculate age
        $age = null;
        if ($request->date_of_birth) {
            $age = \Carbon\Carbon::parse($request->date_of_birth)->age;
        }

        // ── Call Python AI ──
        $ai = new AiEligibilityService();

        $eligibilityInput = [
            'age'             => $age ?? 0,
            'weight_kg'       => $request->weight_kg ?? 0,
            'hemoglobin'      => $request->hemoglobin ?? 0,
            'total_donations' => $request->total_donations ?? 0,
            'blood_group'     => $request->blood_group ?? 'O+',
            'gender'          => $request->gender ?? 'Male',
        ];
        $result = $ai->predict($eligibilityInput);

        // ── Call Python AI — Response Probability ──
        $responseInput = [
            'age'         => $age ?? 0,
            'weight_kg'   => $request->weight_kg  ?? 0,
            'hemoglobin'  => $request->hemoglobin ?? 0,
            'blood_group' => $request->blood_group ?? 'O+',
            'gender'      => $request->gender ?? 'Male',
        ];
        $responseResult = $ai->predictResponse($responseInput);

        // ── Call Python AI — Anomaly Detection ──
        $anomalyInput = [
            'age'             => $age ?? 0,
            'weight_kg'       => $request->weight_kg  ?? 0,
            'hemoglobin'      => $request->hemoglobin ?? 0,
            'total_donations' => $request->total_donations ?? 0,
        ];
        $anomalyResult = $ai->detectAnomaly($anomalyInput);



        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('donor-profiles', 'public');
        }

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
            'profile_image'   => $profileImagePath,
            'medical_condition' => $request->medical_condition,
            'medical_notes'   => $request->medical_notes,
            'is_eligible'     => $result['eligible'],
            'ai_confidence'   => $result['confidence'],
           'response_probability' => $responseResult['response_probability'] ?? 50,
           'response_level'       => $responseResult['level']                ?? 'medium',
           'is_anomaly'           => $anomalyResult['is_anomaly']            ?? false,
            'anomaly_score'        => $anomalyResult['anomaly_score']         ?? 0,
        ]);

        AiPrediction::log($donor->id, 'eligibility', $eligibilityInput, $result);
        AiPrediction::log($donor->id, 'response', $responseInput, $responseResult);
        AiPrediction::log($donor->id, 'anomaly', $anomalyInput, $anomalyResult);

        if ($anomalyResult['is_anomaly'] ?? false) {
            Notification::notifyAllAdmins(
                'AI anomaly alert',
                "AI flagged new donor {$donor->full_name} ({$donor->blood_group}) as a possible anomaly (score {$anomalyResult['anomaly_score']}%).",
                'ai_alert',
                $donor->id
            );
        }

        auth('donor')->login($donor);

        return redirect()->route('donor.dashboard')
                         ->with('success', 'Welcome to LifeLink, ' . $donor->first_name . '! AI eligibility check complete.');
    }
}