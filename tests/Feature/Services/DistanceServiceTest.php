<?php

namespace Tests\Feature\Services;

use App\Services\DistanceService;
use Tests\TestCase;

class DistanceServiceTest extends TestCase
{
    private DistanceService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DistanceService();
    }

    public function test_haversine_distance_between_london_and_paris_is_accurate(): void
    {
        // Textbook great-circle distance, London to Paris: ~343.5km.
        $distance = $this->service->calculate(51.5074, -0.1278, 48.8566, 2.3522);

        $this->assertEqualsWithDelta(343.5, $distance, 2.0);
    }

    public function test_haversine_distance_between_colombo_and_kandy_is_accurate(): void
    {
        // Real Sri Lankan coordinates: Colombo to Kandy, straight-line ~93-95km.
        $distance = $this->service->calculate(6.9271, 79.8612, 7.2906, 80.6337);

        $this->assertEqualsWithDelta(94.0, $distance, 3.0);
    }

    public function test_distance_between_identical_points_is_zero(): void
    {
        $distance = $this->service->calculate(6.9271, 79.8612, 6.9271, 79.8612);

        $this->assertEquals(0.0, $distance);
    }

    public function test_distance_is_symmetric(): void
    {
        $a = $this->service->calculate(6.9271, 79.8612, 7.2906, 80.6337);
        $b = $this->service->calculate(7.2906, 80.6337, 6.9271, 79.8612);

        $this->assertEquals($a, $b);
    }

    public function test_distance_is_rounded_to_one_decimal_place(): void
    {
        $distance = $this->service->calculate(6.9271, 79.8612, 7.2906, 80.6337);

        $this->assertEquals(round($distance, 1), $distance);
    }

    public function test_returns_null_when_any_coordinate_is_missing(): void
    {
        $this->assertNull($this->service->calculate(null, 79.8612, 7.2906, 80.6337));
        $this->assertNull($this->service->calculate(6.9271, null, 7.2906, 80.6337));
        $this->assertNull($this->service->calculate(6.9271, 79.8612, null, 80.6337));
        $this->assertNull($this->service->calculate(6.9271, 79.8612, 7.2906, null));
        $this->assertNull($this->service->calculate(null, null, null, null));
    }

    public function test_travel_category_bands(): void
    {
        $this->assertEquals('Very Near', $this->service->travelCategory(0));
        $this->assertEquals('Very Near', $this->service->travelCategory(5));
        $this->assertEquals('Near', $this->service->travelCategory(5.1));
        $this->assertEquals('Near', $this->service->travelCategory(15));
        $this->assertEquals('Moderate', $this->service->travelCategory(15.1));
        $this->assertEquals('Moderate', $this->service->travelCategory(30));
        $this->assertEquals('Far', $this->service->travelCategory(30.1));
        $this->assertEquals('Far', $this->service->travelCategory(500));
    }

    public function test_travel_category_is_unknown_for_missing_distance(): void
    {
        $this->assertEquals('Unknown', $this->service->travelCategory(null));
    }
}
