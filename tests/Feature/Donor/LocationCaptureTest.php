<?php

namespace Tests\Feature\Donor;

use App\Models\Donor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LocationCaptureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            '*/predict' => Http::response(['eligible' => true, 'confidence' => 90, 'model' => 'k-NN', 'status' => 'success']),
        ]);
    }

    public function test_donor_can_set_their_location(): void
    {
        $donor = Donor::factory()->create(['latitude' => null, 'longitude' => null]);

        $response = $this->actingAs($donor, 'donor')->put(route('donor.profile.update'), [
            'first_name' => $donor->first_name,
            'last_name'  => $donor->last_name,
            'email'      => $donor->email,
            'latitude'   => 6.9271,
            'longitude'  => 79.8612,
        ]);

        $response->assertRedirect();
        $donor->refresh();
        $this->assertEqualsWithDelta(6.9271, $donor->latitude, 0.0001);
        $this->assertEqualsWithDelta(79.8612, $donor->longitude, 0.0001);
    }

    public function test_donor_can_leave_location_blank(): void
    {
        $donor = Donor::factory()->create(['latitude' => null, 'longitude' => null]);

        $response = $this->actingAs($donor, 'donor')->put(route('donor.profile.update'), [
            'first_name' => $donor->first_name,
            'last_name'  => $donor->last_name,
            'email'      => $donor->email,
        ]);

        $response->assertRedirect();
        $response->assertSessionDoesntHaveErrors();
        $this->assertNull($donor->fresh()->latitude);
        $this->assertNull($donor->fresh()->longitude);
    }

    public function test_donor_location_update_rejects_out_of_range_coordinates(): void
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->put(route('donor.profile.update'), [
            'first_name' => $donor->first_name,
            'last_name'  => $donor->last_name,
            'email'      => $donor->email,
            'latitude'   => -200,
            'longitude'  => 79.8612,
        ]);

        $response->assertSessionHasErrors('latitude');
    }

    public function test_donor_profile_edit_page_renders_the_location_picker(): void
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->get(route('donor.profile.edit'));

        $response->assertOk();
        $response->assertSee('locationMap', false);
        $response->assertSee('leaflet', false);
    }

    public function test_donor_dashboard_never_exposes_other_donors_locations(): void
    {
        $donor = Donor::factory()->create();
        Donor::factory()->create(['latitude' => 6.9271, 'longitude' => 79.8612]);

        $response = $this->actingAs($donor, 'donor')->get(route('donor.dashboard'));

        $response->assertOk();
        $response->assertDontSee('79.8612');
    }
}
