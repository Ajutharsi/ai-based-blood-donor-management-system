<?php

namespace Tests\Feature\Hospital;

use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationCaptureTest extends TestCase
{
    use RefreshDatabase;

    public function test_hospital_can_set_its_location(): void
    {
        $hospital = Hospital::factory()->create(['latitude' => null, 'longitude' => null]);

        $response = $this->actingAs($hospital, 'hospital')->put(route('hospital.profile.update'), [
            'name'      => $hospital->name,
            'email'     => $hospital->email,
            'latitude'  => 6.9271,
            'longitude' => 79.8612,
        ]);

        $response->assertRedirect();
        $hospital->refresh();
        $this->assertEqualsWithDelta(6.9271, $hospital->latitude, 0.0001);
        $this->assertEqualsWithDelta(79.8612, $hospital->longitude, 0.0001);
    }

    public function test_hospital_can_leave_location_blank(): void
    {
        $hospital = Hospital::factory()->create(['latitude' => null, 'longitude' => null]);

        $response = $this->actingAs($hospital, 'hospital')->put(route('hospital.profile.update'), [
            'name'  => $hospital->name,
            'email' => $hospital->email,
        ]);

        $response->assertRedirect();
        $response->assertSessionDoesntHaveErrors();
        $this->assertNull($hospital->fresh()->latitude);
        $this->assertNull($hospital->fresh()->longitude);
    }

    public function test_hospital_location_update_rejects_out_of_range_latitude(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->put(route('hospital.profile.update'), [
            'name'      => $hospital->name,
            'email'     => $hospital->email,
            'latitude'  => 200,
            'longitude' => 79.8612,
        ]);

        $response->assertSessionHasErrors('latitude');
    }

    public function test_hospital_location_update_rejects_out_of_range_longitude(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->put(route('hospital.profile.update'), [
            'name'      => $hospital->name,
            'email'     => $hospital->email,
            'latitude'  => 6.9271,
            'longitude' => 200,
        ]);

        $response->assertSessionHasErrors('longitude');
    }

    public function test_hospital_profile_edit_page_renders_the_location_picker(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->get(route('hospital.profile.edit'));

        $response->assertOk();
        $response->assertSee('locationMap', false);
        $response->assertSee('leaflet', false);
    }
}
