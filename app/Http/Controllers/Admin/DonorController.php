<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
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
        return view('admin.donors.show', compact('donor'));
    }

    // Toggle donor eligibility manually
    public function toggleEligibility(Donor $donor)
    {
        // $donor->update([
        //     'is_eligible' => !$donor->is_eligible,
        // ]);

        $donor->update([
    'is_eligible'    => $newEligible,
    'ai_confidence'  => $newConfidence,
    'last_ai_check'  => now(),  
]);

        return back()->with('success',
            $donor->full_name . ' marked as ' .
            ($donor->is_eligible ? 'eligible' : 'not eligible') . '.'
        );
    }

    // Delete donor
    public function destroy(Donor $donor)
    {
        $donor->delete();
        return redirect()->route('admin.donors.index')
                         ->with('success', 'Donor deleted successfully.');
    }
}