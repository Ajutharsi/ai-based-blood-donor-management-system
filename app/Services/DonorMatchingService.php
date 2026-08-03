<?php

namespace App\Services;

use App\Models\BloodRequest;
use App\Models\Donor;
use App\Support\BloodCompatibility;
use Illuminate\Support\Collection;

/**
 * Ranks eligible donors against a specific blood request.
 *
 * This is a transparent, explainable weighted score -- not a trained
 * classifier. There is no historical "this donor was contacted and it
 * worked out" data anywhere in the app to train a real supervised model
 * on (the closest attempt at that, response-prediction, was found to be
 * built on a label leaked from its own input features and has been fixed
 * separately). Rather than repeat that mistake here by inventing labels
 * for a matching classifier, this combines real signals that already
 * exist and were honestly computed by real trained models -- donor
 * ai_confidence (the k-NN eligibility model) and response_probability
 * (the profile-based repeat-donor model) -- with correct, deterministic
 * domain logic: ABO/Rh compatibility and urgency-aware weighting.
 */
class DonorMatchingService
{
    // Urgency changes how heavily immediacy (compatibility exactness,
    // proximity) is weighted against overall donor reliability. A critical
    // request cares most about "can we reach a compatible donor right now";
    // a standard request can afford to optimise for the best overall match.
    private const URGENCY_WEIGHTS = [
        'critical' => ['compatibility' => 0.30, 'district' => 0.30, 'confidence' => 0.20, 'response' => 0.20],
        'urgent'   => ['compatibility' => 0.30, 'district' => 0.20, 'confidence' => 0.25, 'response' => 0.25],
        'standard' => ['compatibility' => 0.25, 'district' => 0.10, 'confidence' => 0.30, 'response' => 0.35],
    ];

    private const EXACT_MATCH_SCORE     = 100;
    private const COMPATIBLE_MATCH_SCORE = 65;
    private const SAME_DISTRICT_SCORE   = 100;
    private const OTHER_DISTRICT_SCORE  = 40;

    /**
     * @return Collection<int, Donor> eligible, blood-compatible donors for
     *   this request, ordered by match_score descending, each carrying a
     *   dynamic match_score and match_breakdown attribute.
     */
    public function findMatches(BloodRequest $bloodRequest, int $limit = 10): Collection
    {
        $compatibleGroups = BloodCompatibility::compatibleDonorGroups($bloodRequest->blood_group);
        $hospitalDistrict  = $bloodRequest->hospital?->district;
        $weights           = self::URGENCY_WEIGHTS[$bloodRequest->urgency] ?? self::URGENCY_WEIGHTS['standard'];

        $candidates = Donor::whereIn('blood_group', $compatibleGroups)
            ->where('is_eligible', true)
            ->get();

        return $candidates
            ->map(function (Donor $donor) use ($bloodRequest, $hospitalDistrict, $weights) {
                $exactMatch    = BloodCompatibility::isExactMatch($donor->blood_group, $bloodRequest->blood_group);
                $sameDistrict  = $hospitalDistrict !== null && $donor->district === $hospitalDistrict;

                $compatibilityScore = $exactMatch ? self::EXACT_MATCH_SCORE : self::COMPATIBLE_MATCH_SCORE;
                $districtScore      = $sameDistrict ? self::SAME_DISTRICT_SCORE : self::OTHER_DISTRICT_SCORE;
                $confidenceScore    = (float) ($donor->ai_confidence ?? 0);
                $responseScore      = (float) ($donor->response_probability ?? 0);

                $matchScore =
                    $weights['compatibility'] * $compatibilityScore +
                    $weights['district']      * $districtScore +
                    $weights['confidence']    * $confidenceScore +
                    $weights['response']      * $responseScore;

                $donor->match_score = round($matchScore, 1);
                $donor->match_breakdown = [
                    'exact_blood_match' => $exactMatch,
                    'same_district'     => $sameDistrict,
                    'ai_confidence'     => $confidenceScore,
                    'response_probability' => $responseScore,
                ];

                return $donor;
            })
            ->sortByDesc('match_score')
            ->values()
            ->take($limit);
    }
}
