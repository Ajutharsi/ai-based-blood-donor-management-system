<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    // Show login form
    public function showForm()
    {
       
        return view('common.login_page', ['role' => 'donor']);
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (auth('donor')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $donor = auth('donor')->user();

            ActivityLog::record('donor', $donor->id, $donor->full_name, 'auth', 'login', __(':name logged in.', ['name' => $donor->full_name]));

            return redirect()->route('donor.dashboard')
                             ->with('success', 'Welcome back, ' . $donor->first_name . '!');
        }

        Log::warning('Failed donor login attempt', ['email' => $request->input('email'), 'ip' => $request->ip()]);

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    // Logout
    public function logout(Request $request)
    {
        $donor = auth('donor')->user();
        if ($donor) {
            ActivityLog::record('donor', $donor->id, $donor->full_name, 'auth', 'logout', __(':name logged out.', ['name' => $donor->full_name]));
        }

        auth('donor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('donor.login');
    }
}