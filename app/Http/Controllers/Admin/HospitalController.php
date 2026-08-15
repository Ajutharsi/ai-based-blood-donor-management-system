<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Hospital;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    // List all hospitals with search + filter
    public function index(Request $request)
    {
        $query = Hospital::query();

        // Search by name, email, or registration ID. LIKE wildcards are
        // stripped from the input so "%"/"_" can't cause a broad-match.
        if ($request->filled('search')) {
            $search = str_replace(['%', '_'], '', $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('registration_id', 'like', "%$search%");
            });
        }

        // Filter by verification status
        if ($request->filled('status')) {
            $query->where('is_verified', $request->status === 'verified' ? 1 : 0);
        }

        // Filter by district
        if ($request->filled('district')) {
            $query->where('district', $request->district);
        }

        $hospitals = $query->withCount('bloodRequests')->latest()->paginate(15);

        return view('admin.hospitals.index', compact('hospitals'));
    }

    // View single hospital
    public function show(Hospital $hospital)
    {
        $hospital->load(['bloodRequests' => fn ($q) => $q->latest()]);

        return view('admin.hospitals.show', compact('hospital'));
    }

    // Toggle hospital verification manually
    public function toggleVerification(Hospital $hospital)
    {
        $hospital->update(['is_verified' => !$hospital->is_verified]);

        ActivityLog::log(
            'admin', 'hospital_verification_toggled',
            __('Admin marked :hospital as :status.', ['hospital' => $hospital->name, 'status' => $hospital->is_verified ? 'verified' : 'unverified']),
            'Hospital', $hospital->id
        );

        return back()->with('success',
            $hospital->name . ' marked as ' .
            ($hospital->is_verified ? 'verified' : 'unverified') . '.'
        );
    }

    // Delete hospital
    public function destroy(Hospital $hospital)
    {
        $name = $hospital->name;
        $id = $hospital->id;
        $hospital->delete();

        ActivityLog::log('admin', 'hospital_deleted', __('Admin deleted hospital :name.', ['name' => $name]), 'Hospital', $id);

        return redirect()->route('admin.hospitals.index')
                         ->with('success', 'Hospital deleted successfully.');
    }
}
