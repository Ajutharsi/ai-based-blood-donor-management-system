<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Services\DonorMatchingService;
use Illuminate\Http\Request;

class BloodRequestController extends Controller
{
    public function __construct(private DonorMatchingService $matchingService)
    {
    }

    // The request form lives on the hospital dashboard
    public function create()
    {
        return redirect()->route('hospital.dashboard');
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

        // AI-ranked donor matching: blood/Rh compatibility (not just an exact
        // blood-group match), district proximity, and each donor's existing
        // AI-computed eligibility confidence and response likelihood, weighted
        // by this request's urgency. See DonorMatchingService for the scoring.
        $matched_donors = $this->matchingService->findMatches($bloodRequest, 10);

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
        if ($bloodRequest->hospital_id !== auth('hospital')->id()) {
            abort(403);
        }

        $bloodRequest->update(['status' => 'fulfilled']);
        return back()->with('success', 'Blood request marked as fulfilled.');
    }
}