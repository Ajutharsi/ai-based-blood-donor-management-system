<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    // Show login form
    public function showForm()
    {
       
        return view('common.login_page');
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
            return redirect()->route('donor.dashboard')
                             ->with('success', 'Welcome back, ' . auth('donor')->user()->first_name . '!');
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    // Logout
    public function logout(Request $request)
    {
        auth('donor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('donor.login');
    }
}