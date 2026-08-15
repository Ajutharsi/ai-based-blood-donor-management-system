<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BloodRequest;
use App\Models\DonorResponse;
use App\Models\Notification;
use App\Support\BloodCompatibility;
use Illuminate\Http\Request;

class BloodRequestController extends Controller
{
    private const URGENCY_ORDER = ['critical' => 0, 'urgent' => 1, 'standard' => 2];

    // Pending requests this donor is blood/Rh compatible with, so a donor
    // only ever sees requests they could actually give to -- not every
    // open request in the system.
    public function index()
    {
        $donor = auth('donor')->user();

        $requests = collect();

        if ($donor->is_eligible) {
            $requests = BloodRequest::with('hospital')
                ->where('status', 'pending')
                ->get()
                ->filter(fn (BloodRequest $r) => in_array(
                    $donor->blood_group,
                    BloodCompatibility::compatibleDonorGroups($r->blood_group)
                ))
                ->sortBy([
                    fn ($r) => $r->hospital?->district === $donor->district ? 0 : 1,
                    fn ($r) => self::URGENCY_ORDER[$r->urgency] ?? 2,
                    fn ($r) => $r->created_at->timestamp * -1,
                ])
                ->values();
        }

        $myResponses = DonorResponse::where('donor_id', $donor->id)
            ->whereIn('blood_request_id', $requests->pluck('id'))
            ->get()
            ->keyBy('blood_request_id');

        return view('donor.blood_requests', compact('donor', 'requests', 'myResponses'));
    }

    // Record whether this donor is available for a specific compatible
    // request. Re-submitting updates the same row rather than stacking up
    // duplicate responses (see the unique donor_id+blood_request_id index).
    public function respond(Request $request, BloodRequest $bloodRequest)
    {
        $request->validate([
            'status' => 'required|in:available,not_available',
        ]);

        $donor = auth('donor')->user();

        $compatible = in_array(
            $donor->blood_group,
            BloodCompatibility::compatibleDonorGroups($bloodRequest->blood_group)
        );

        if (!$donor->is_eligible || !$compatible) {
            abort(403);
        }

        DonorResponse::updateOrCreate(
            ['donor_id' => $donor->id, 'blood_request_id' => $bloodRequest->id],
            ['status' => $request->status, 'responded_at' => now()]
        );

        $responseLabel = $request->status === 'available' ? 'available' : 'not available';

        ActivityLog::log(
            'blood_request', 'blood_request_responded',
            __(':donor responded ":response" to the :group request.', ['donor' => $donor->full_name, 'response' => $responseLabel, 'group' => $bloodRequest->blood_group]),
            'BloodRequest', $bloodRequest->id
        );
        Notification::notify(
            'hospital',
            $bloodRequest->hospital_id,
            'Donor response',
            "{$donor->full_name} responded '{$responseLabel}' to your {$bloodRequest->blood_group} request.",
            'donor_response',
            $bloodRequest->id
        );

        $message = $request->status === 'available'
            ? "Thanks — you've been marked as available for this request."
            : 'Noted — you have been marked as not available for this request.';

        return back()->with('success', $message);
    }
}
