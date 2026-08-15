<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Mail\BloodRequestNotification;
use App\Models\ActivityLog;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\DonorResponse;
use App\Models\Notification;
use App\Services\BloodInventoryService;
use App\Services\DonorMatchingService;
use App\Support\BloodCompatibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BloodRequestController extends Controller
{
    public function __construct(
        private DonorMatchingService $matchingService,
        private BloodInventoryService $inventoryService,
    ) {
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
            'blood_group'  => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'units_needed' => 'required|integer|min:1|max:20',
            'urgency'      => 'required|in:standard,urgent,critical',
            'ward'         => 'nullable|string|max:100',
            'required_by'  => 'nullable|date',
            'notes'        => 'nullable|string|max:1000',
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

        ActivityLog::log(
            'blood_request', 'blood_request_created',
            __(':hospital requested :units unit(s) of :group (:urgency).', [
                'hospital' => $hospital->name, 'units' => $bloodRequest->units_needed, 'group' => $bloodRequest->blood_group, 'urgency' => $bloodRequest->urgency,
            ]),
            'BloodRequest', $bloodRequest->id
        );

        $this->notifyOnNewRequest($bloodRequest, $hospital);

        return $this->renderMatches($bloodRequest, $hospital);
    }

    // In-app notifications triggered the moment a request is submitted:
    // the top AI-matched donors are told they're a match, and -- for
    // critical requests specifically -- every admin gets a shortage alert.
    // This runs regardless of whether the hospital goes on to use the
    // explicit "Notify" button on any particular donor.
    private function notifyOnNewRequest(BloodRequest $bloodRequest, $hospital): void
    {
        $topMatches = $this->matchingService->findMatches($bloodRequest, 5);

        foreach ($topMatches as $donor) {
            Notification::notify(
                'donor',
                $donor->id,
                __('You are a match for a blood request'),
                __('You are eligible for a blood donation request: :group needed at :hospital.', [
                    'group'    => $bloodRequest->blood_group,
                    'hospital' => $hospital->name,
                ]),
                'donor_match',
                $bloodRequest->id
            );
        }

        if ($bloodRequest->urgency === 'critical') {
            Notification::notifyAllAdmins(
                __('Critical blood shortage'),
                __(':hospital urgently needs :units unit(s) of :group.', [
                    'hospital' => $hospital->name,
                    'units'    => $bloodRequest->units_needed,
                    'group'    => $bloodRequest->blood_group,
                ]),
                'shortage_alert',
                $bloodRequest->id
            );
        }
    }

    // Revisit an existing request's matched donors later (e.g. to check
    // whether anyone has responded since it was first submitted) rather
    // than only being able to see matches once, immediately after creation.
    public function show(Request $request, BloodRequest $bloodRequest)
    {
        $hospital = auth('hospital')->user();

        if ($bloodRequest->hospital_id !== $hospital->id) {
            abort(403);
        }

        return $this->renderMatches($bloodRequest, $hospital, $request->get('sort', 'match_score'));
    }

    private function renderMatches(BloodRequest $bloodRequest, $hospital, string $sort = 'match_score')
    {
        // AI-ranked donor matching: blood/Rh compatibility (not just an exact
        // blood-group match), district proximity, real hospital-to-donor
        // distance, and each donor's existing AI-computed eligibility
        // confidence and response likelihood, weighted by this request's
        // urgency. See DonorMatchingService for the scoring. findMatches()
        // itself returns match_score-descending; re-sort here only if the
        // hospital asked for a different view.
        $matched_donors = $this->matchingService->findMatches($bloodRequest, 10);

        $matched_donors = match ($sort) {
            'distance'   => $matched_donors->sortBy(fn ($d) => $d->distance_km ?? PHP_FLOAT_MAX)->values(),
            'confidence' => $matched_donors->sortByDesc(fn ($d) => (float) ($d->ai_confidence ?? 0))->values(),
            default      => $matched_donors,
        };

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
            'donorResponses',
            'sort'
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

        Notification::notify(
            'donor',
            $donor->id,
            __('Blood request'),
            __('Emergency blood request: :group needed at :hospital', [
                'group'    => $bloodRequest->blood_group,
                'hospital' => $hospital->name,
            ]),
            'blood_request',
            $bloodRequest->id
        );

        ActivityLog::log(
            'blood_request', 'blood_request_donor_notified',
            __(':hospital emailed :donor about the :group request.', ['hospital' => $hospital->name, 'donor' => $donor->full_name, 'group' => $bloodRequest->blood_group]),
            'BloodRequest', $bloodRequest->id
        );

        return back()->with('success', "Notification emailed to {$donor->full_name}.");
    }

    // Mark request as fulfilled
    public function fulfill(BloodRequest $bloodRequest)
    {
        if ($bloodRequest->hospital_id !== auth('hospital')->id()) {
            abort(403);
        }

        $bloodRequest->update(['status' => 'fulfilled']);

        $hospital = auth('hospital')->user();

        ActivityLog::log(
            'blood_request', 'blood_request_fulfilled',
            __(':hospital marked the :group request as fulfilled.', ['hospital' => $hospital->name, 'group' => $bloodRequest->blood_group]),
            'BloodRequest', $bloodRequest->id
        );
        $respondedAvailable = DonorResponse::where('blood_request_id', $bloodRequest->id)
            ->where('status', 'available')
            ->pluck('donor_id');

        foreach ($respondedAvailable as $donorId) {
            Notification::notify(
                'donor',
                $donorId,
                __('Request fulfilled'),
                __('The :group request at :hospital has been fulfilled. Thank you for responding!', [
                    'group'    => $bloodRequest->blood_group,
                    'hospital' => $hospital->name,
                ]),
                'fulfillment',
                $bloodRequest->id
            );
        }

        // Auto-deduct fulfilled units from recorded stock -- never goes
        // below zero, and flags it (without blocking the fulfillment) if
        // recorded stock couldn't fully cover what was needed.
        $deduction = $this->inventoryService->deductForFulfillment(
            $hospital->id, $bloodRequest->blood_group, $bloodRequest->units_needed
        );

        if ($deduction['tracked'] && !$deduction['fully_covered']) {
            return back()->with('success', 'Blood request marked as fulfilled.')
                ->with('warning', "Recorded {$bloodRequest->blood_group} stock only covered {$deduction['removed']} of the {$bloodRequest->units_needed} unit(s) needed -- inventory is now at 0. Please update stock manually if more was actually used.");
        }

        return back()->with('success', 'Blood request marked as fulfilled.');
    }
}