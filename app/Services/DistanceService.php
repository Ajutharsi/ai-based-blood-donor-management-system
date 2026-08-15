<?php

namespace App\Services;

/**
 * Great-circle distance between two lat/lng points via the Haversine
 * formula. Every method is null-safe: any missing coordinate returns null
 * rather than throwing, so callers (DonorMatchingService, dashboards) can
 * treat "location not set yet" as a normal, expected state -- not an error.
 */
class DistanceService
{
    private const EARTH_RADIUS_KM = 6371.0;

    /**
     * @return float|null Distance in kilometres, rounded to 1 decimal
     *   place, or null if any coordinate is missing.
     */
    public function calculate(?float $lat1, ?float $lng1, ?float $lat2, ?float $lng2): ?float
    {
        if ($lat1 === null || $lng1 === null || $lat2 === null || $lng2 === null) {
            return null;
        }

        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lngDelta / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round(self::EARTH_RADIUS_KM * $c, 1);
    }

    // 0-5 km Very Near, 5-15 km Near, 15-30 km Moderate, 30+ km Far --
    // matches the bands the Matched Donors page displays them in.
    public function travelCategory(?float $distanceKm): string
    {
        if ($distanceKm === null) {
            return 'Unknown';
        }

        return match (true) {
            $distanceKm <= 5   => 'Very Near',
            $distanceKm <= 15  => 'Near',
            $distanceKm <= 30  => 'Moderate',
            default            => 'Far',
        };
    }
}
