<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\UpdateHospitalProfileRequest;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $hospital = auth('hospital')->user();

        return view('hospital.hospital_profile_edit', compact('hospital'));
    }

    public function update(UpdateHospitalProfileRequest $request)
    {
        $hospital = auth('hospital')->user();

        $data = [
            'name'            => $request->name,
            'email'           => $request->email,
            'registration_id' => $request->registration_id,
            'phone'           => $request->phone,
            'city'            => $request->city,
            'district'        => $request->district,
            'address'         => $request->address,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $hospital->update($data);

        return redirect()->route('hospital.dashboard')
                         ->with('success', 'Your hospital profile has been updated.');
    }
}
