<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Services\AiEligibilityService;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    // List all donors with search + filter
    public function index(Request $request)
    {
        $query = Donor::query();

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name',  'like', "%$search%")
                  ->orWhere('email',      'like', "%$search%");
            });
        }

        // Filter by blood group
        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        // Filter by eligibility
        if ($request->filled('status')) {
            $query->where('is_eligible', $request->status === 'eligible' ? 1 : 0);
        }

        // Filter by district
        if ($request->filled('district')) {
            $query->where('district', $request->district);
        }

        $donors = $query->latest()->paginate(15);

        return view('admin.donors.index', compact('donors'));
    }




    // View single donor
    public function show(Donor $donor)
    {
        $modelMetrics = (new AiEligibilityService())->getModelInfo();

        return view('admin.donors.show', compact('donor', 'modelMetrics'));
    }

    // Toggle donor eligibility manually
    public function toggleEligibility(Donor $donor)
    {
        $donor->update([
            'is_eligible'   => !$donor->is_eligible,
            'last_ai_check' => now(),
        ]);

        return back()->with('success',
            $donor->full_name . ' marked as ' .
            ($donor->is_eligible ? 'eligible' : 'not eligible') . '.'
        );
    }

    // Record a completed donation for a donor
    public function storeDonation(Request $request, Donor $donor)
    {
        $validated = $request->validate([
            'donation_date'   => 'required|date|before_or_equal:today',
            'donation_center' => 'nullable|string|max:150',
            'units'           => 'nullable|integer|min:1|max:5',
            'notes'           => 'nullable|string|max:500',
        ]);

        $donor->donations()->create([
            'donation_date'   => $validated['donation_date'],
            'blood_group'     => $donor->blood_group,
            'donation_center' => $validated['donation_center'] ?? $donor->donation_center,
            'units'           => $validated['units'] ?? 1,
            'notes'           => $validated['notes'] ?? null,
        ]);

        $donor->increment('total_donations');

        if (!$donor->last_donation_date || $validated['donation_date'] > $donor->last_donation_date->format('Y-m-d')) {
            $donor->update(['last_donation_date' => $validated['donation_date']]);
        }

        return back()->with('success', 'Donation recorded for ' . $donor->full_name . '.');
    }

    // Delete donor
    public function destroy(Donor $donor)
    {
        $donor->delete();
        return redirect()->route('admin.donors.index')
                         ->with('success', 'Donor deleted successfully.');
    }
}