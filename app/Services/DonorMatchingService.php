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
 * domain logic: ABO/Rh compatibility, actual hospital-to-donor distance,
 * and urgency-aware weighting.
 */
class DonorMatchingService
{
    // Urgency changes how heavily immediacy (compatibility exactness,
    // proximity) is weighted against overall donor reliability. A critical
    // request cares most about "can we reach a compatible donor right now",
    // so distance becomes one of the highest-weight factors; a standard
    // request can afford to optimise for the best overall match. Each
    // tier's weights sum to 1.0.
    private const URGENCY_WEIGHTS = [
        'critical' => ['compatibility' => 0.20, 'district' => 0.15, 'confidence' => 0.15, 'response' => 0.15, 'distance' => 0.35],
        'urgent'   => ['compatibility' => 0.20, 'district' => 0.10, 'confidence' => 0.20, 'response' => 0.20, 'distance' => 0.30],
        'standard' => ['compatibility' => 0.20, 'district' => 0.10, 'confidence' => 0.25, 'response' => 0.30, 'distance' => 0.15],
    ];

    private const EXACT_MATCH_SCORE     = 100;
    private const COMPATIBLE_MATCH_SCORE = 65;
    private const SAME_DISTRICT_SCORE   = 100;
    private const OTHER_DISTRICT_SCORE  = 40;

    // Beyond this radius, distance stops discriminating further (score
    // bottoms out at 0) -- distance still matters, but the score isn't
    // meaningfully "worse" for a donor 200km away vs. 400km away.
    private const MAX_RELEVANT_DISTANCE_KM = 100.0;

    // A donor or hospital with no location set yet ("existing users may
    // leave location blank") gets a neutral distance score -- neither
    // rewarded nor penalised for a signal that simply isn't available.
    private const NEUTRAL_DISTANCE_SCORE = 50.0;

    public function __construct(private DistanceService $distanceService)
    {
    }

    /**
     * @return Collection<int, Donor> eligible, blood-compatible donors for
     *   this request, ordered by match_score descending, each carrying a
     *   dynamic match_score, match_breakdown, distance_km, and
     *   travel_category attribute.
     */
    public function findMatches(BloodRequest $bloodRequest, int $limit = 10): Collection
    {
        $compatibleGroups = BloodCompatibility::compatibleDonorGroups($bloodRequest->blood_group);
        $hospital          = $bloodRequest->hospital;
        $hospitalDistrict  = $hospital?->district;
        $weights           = self::URGENCY_WEIGHTS[$bloodRequest->urgency] ?? self::URGENCY_WEIGHTS['standard'];

        $candidates = Donor::whereIn('blood_group', $compatibleGroups)
            ->where('is_eligible', true)
            ->get();

        return $candidates
            ->map(function (Donor $donor) use ($bloodRequest, $hospital, $hospitalDistrict, $weights) {
                $exactMatch    = BloodCompatibility::isExactMatch($donor->blood_group, $bloodRequest->blood_group);
                $sameDistrict  = $hospitalDistrict !== null && $donor->district === $hospitalDistrict;

                $distanceKm = $hospital
                    ? $this->distanceService->calculate($hospital->latitude, $hospital->longitude, $donor->latitude, $donor->longitude)
                    : null;

                $compatibilityScore = $exactMatch ? self::EXACT_MATCH_SCORE : self::COMPATIBLE_MATCH_SCORE;
                $districtScore      = $sameDistrict ? self::SAME_DISTRICT_SCORE : self::OTHER_DISTRICT_SCORE;
                $confidenceScore    = (float) ($donor->ai_confidence ?? 0);
                $responseScore      = (float) ($donor->response_probability ?? 0);
                $distanceScore      = $this->scoreForDistance($distanceKm);

                $matchScore =
                    $weights['compatibility'] * $compatibilityScore +
                    $weights['district']      * $districtScore +
                    $weights['confidence']    * $confidenceScore +
                    $weights['response']      * $responseScore +
                    $weights['distance']      * $distanceScore;

                $donor->match_score = round($matchScore, 1);
                $donor->distance_km = $distanceKm;
                $donor->travel_category = $this->distanceService->travelCategory($distanceKm);
                $donor->match_breakdown = [
                    'exact_blood_match'    => $exactMatch,
                    'same_district'        => $sameDistrict,
                    'ai_confidence'        => $confidenceScore,
                    'response_probability' => $responseScore,
                    'distance_km'          => $distanceKm,
                    'distance_score'       => round($distanceScore, 1),
                ];

                return $donor;
            })
            ->sortByDesc('match_score')
            ->values()
            ->take($limit);
    }

    // Linear decay from 100 (0km) to 0 (MAX_RELEVANT_DISTANCE_KM or
    // further). Null (either party has no location set) falls back to a
    // neutral mid-point rather than 0 or 100, so missing coordinates never
    // silently become "worst possible" or "best possible" for a donor.
    private function scoreForDistance(?float $distanceKm): float
    {
        if ($distanceKm === null) {
            return self::NEUTRAL_DISTANCE_SCORE;
        }

        $clamped = min($distanceKm, self::MAX_RELEVANT_DISTANCE_KM);

        return max(0.0, 100.0 - ($clamped / self::MAX_RELEVANT_DISTANCE_KM) * 100.0);
    }
}
