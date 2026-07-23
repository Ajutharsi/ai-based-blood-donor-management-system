<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\Donor;
use Illuminate\Http\Request;

class BloodRequestController extends Controller
{
    // Show request form
    public function create()
    {
        return view('hospital.request');
    }

    // Save blood request + find AI matched donors
    public function store(Request $request)
    {
        $request->validate([
            'blood_group'  => 'required|string',
            'units_needed' => 'required|integer|min:1|max:20',
            'urgency'      => 'required|in:standard,urgent,critical',
            'ward'         => 'nullable|string',
            'required_by'  => 'nullable|date',
            'notes'        => 'nullable|string',
        ]);

        $hospital = auth('hospital')->user();

        // Save request to DB
        $bloodRequest = BloodRequest::create([
            'hospital_id'  => $hospital->id,
            'blood_group'  => $request->blood_group,
            'units_needed' => $request->units_needed,
            'urgency'      => $request->urgency,
            'ward'         => $request->ward,
            'required_by'  => $request->required_by,
            'notes'        => $request->notes,
            'status'       => 'pending',
        ]);

        // Find matching eligible donors
        $matched_donors = Donor::where('blood_group', $request->blood_group)
                            ->where('is_eligible', true)
                            ->orderByDesc('ai_confidence')
                            ->take(10)
                            ->get();

        return view('hospital.matched_donors', compact(
            'bloodRequest',
            'matched_donors',
            'hospital'
        ));
    }

    // All requests history
    public function index(Request $request)
{
    $hospital = auth('hospital')->user();

    $query = BloodRequest::where('hospital_id', $hospital->id);

    if ($request->filled('blood_group')) {
        $query->where('blood_group', $request->blood_group);
    }
    if ($request->filled('urgency')) {
        $query->where('urgency', $request->urgency);
    }
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $requests = $query->latest()->paginate(10);

    return view('hospital.requests', compact('requests', 'hospital'));
}

    // Mark request as fulfilled
    public function fulfill(BloodRequest $bloodRequest)
    {
        $bloodRequest->update(['status' => 'fulfilled']);
        return back()->with('success', 'Blood request marked as fulfilled.');
    }
}