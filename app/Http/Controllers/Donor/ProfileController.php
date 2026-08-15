<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Donor\UpdateProfileRequest;
use App\Models\ActivityLog;
use App\Models\AiPrediction;
use App\Services\AiEligibilityService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $donor = auth('donor')->user();

        return view('donor.donor_profile_edit', compact('donor'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $donor = auth('donor')->user();

        $age = $donor->age;
        if ($request->date_of_birth) {
            $age = \Carbon\Carbon::parse($request->date_of_birth)->age;
        }

        $ai = new AiEligibilityService();
        $eligibilityInput = [
            'age'             => $age ?? 0,
            'weight_kg'       => $request->weight_kg ?? 0,
            'hemoglobin'      => $request->hemoglobin ?? 0,
            'total_donations' => $donor->total_donations ?? 0,
            'blood_group'     => $request->blood_group ?? 'O+',
            'gender'          => $request->gender ?? 'Male',
        ];
        $result = $ai->predict($eligibilityInput);

        $data = [
            'first_name'         => $request->first_name,
            'last_name'          => $request->last_name,
            'email'              => $request->email,
            'phone'              => $request->phone,
            'date_of_birth'      => $request->date_of_birth,
            'age'                => $age,
            'gender'             => $request->gender,
            'nic'                => $request->nic,
            'blood_group'        => $request->blood_group,
            'weight_kg'          => $request->weight_kg,
            'hemoglobin'         => $request->hemoglobin,
            'city'               => $request->city,
            'district'           => $request->district,
            'latitude'           => $request->latitude,
            'longitude'          => $request->longitude,
            'donation_center'    => $request->donation_center,
            'medical_condition'  => $request->medical_condition,
            'medical_notes'      => $request->medical_notes,
            'is_eligible'        => $result['eligible'],
            'ai_confidence'      => $result['confidence'],
            'last_ai_check'      => now(),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_image')) {
            if ($donor->profile_image) {
                Storage::disk('public')->delete($donor->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('donor-profiles', 'public');
        }

        $donor->update($data);

        ActivityLog::log('profile', 'profile_updated', __(':name updated their profile.', ['name' => $donor->full_name]), 'Donor', $donor->id);

        AiPrediction::log($donor->id, 'eligibility', $eligibilityInput, $result);

        return redirect()->route('donor.dashboard')
                         ->with('success', 'Your profile has been updated.');
    }
}
