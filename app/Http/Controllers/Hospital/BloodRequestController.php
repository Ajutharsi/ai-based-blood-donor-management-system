<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Mail\BloodRequestNotification;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\DonorResponse;
use App\Services\DonorMatchingService;
use App\Support\BloodCompatibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

        return $this->renderMatches($bloodRequest, $hospital);
    }

    // Revisit an existing request's matched donors later (e.g. to check
    // whether anyone has responded since it was first submitted) rather
    // than only being able to see matches once, immediately after creation.
    public function show(BloodRequest $bloodRequest)
    {
        $hospital = auth('hospital')->user();

        if ($bloodRequest->hospital_id !== $hospital->id) {
            abort(403);
        }

        return $this->renderMatches($bloodRequest, $hospital);
    }

    private function renderMatches(BloodRequest $bloodRequest, $hospital)
    {
        // AI-ranked donor matching: blood/Rh compatibility (not just an exact
        // blood-group match), district proximity, and each donor's existing
        // AI-computed eligibility confidence and response likelihood, weighted
        // by this request's urgency. See DonorMatchingService for the scoring.
        $matched_donors = $this->matchingService->findMatches($bloodRequest, 10);

        // Real donor responses recorded so far for this request (Donor
        // Blood Requests page), keyed by donor_id, so the hospital sees
        // whether a matched donor has actually confirmed availability
        // rather than only the AI-derived match score.
        $donorResponses = DonorResponse::where('blood_request_id', $bloodRequest->id)
            ->get()
            ->keyBy('donor_id');

        return view('hospital.matched_donors', compact(
            'bloodRequest',
            'matched_donors',
            'hospital',
            'donorResponses'
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

    // Email a specific matched donor about this request. Restricted to
    // donors who are genuinely eligible and blood/Rh compatible with the
    // request, so the route can't be used to email an arbitrary donor.
    public function notifyDonor(BloodRequest $bloodRequest, Donor $donor)
    {
        $hospital = auth('hospital')->user();

        if ($bloodRequest->hospital_id !== $hospital->id) {
            abort(403);
        }

        $compatible = $donor->is_eligible && in_array(
            $donor->blood_group,
            BloodCompatibility::compatibleDonorGroups($bloodRequest->blood_group)
        );

        if (!$compatible) {
            abort(403);
        }

        Mail::to($donor->email)->send(new BloodRequestNotification($donor, $bloodRequest, $hospital));

        return back()->with('success', "Notification emailed to {$donor->full_name}.");
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