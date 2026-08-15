<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\RegisterHospitalRequest;
use App\Models\ActivityLog;
use App\Models\Hospital;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('hospital.hospital_registration_page');
    }

    public function register(RegisterHospitalRequest $request)
    {
        // Self-registered hospitals start unverified (is_verified defaults to
        // false) until an admin verifies them -- not set here.
        $hospital = Hospital::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'registration_id' => $request->registration_id,
            'phone'           => $request->phone,
            'city'            => $request->city,
            'district'        => $request->district,
            'address'         => $request->address,
        ]);

        ActivityLog::record(
            'hospital', $hospital->id, $hospital->name, 'registration', 'hospital_registered',
            __(':name registered as a hospital.', ['name' => $hospital->name]),
            'Hospital', $hospital->id
        );

        Notification::notifyAllAdmins(
            __('New hospital registration'),
            __(':hospital registered from :district and is pending verification.', [
                'hospital' => $hospital->name,
                'district' => $hospital->district,
            ]),
            'hospital_registration',
            $hospital->id
        );

        auth('hospital')->login($hospital);

        return redirect()->route('hospital.dashboard')
                         ->with('success', 'Welcome to LifeLink, ' . $hospital->name . '! Your hospital account has been created.');
    }
}
