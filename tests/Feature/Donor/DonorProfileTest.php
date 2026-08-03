<?php

namespace Tests\Feature\Donor;

use App\Models\Donor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DonorProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            '*/predict' => Http::response(['eligible' => true, 'confidence' => 90]),
        ]);
    }

    public function test_guest_cannot_access_profile_edit_page(): void
    {
        $response = $this->get(route('donor.profile.edit'));

        $response->assertRedirect(route('donor.login'));
    }

    public function test_donor_can_view_edit_profile_page(): void
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->get(route('donor.profile.edit'));

        $response->assertOk();
        $response->assertSee($donor->email);
    }

    public function test_donor_can_update_profile(): void
    {
        $donor = Donor::factory()->create(['first_name' => 'Old']);

        $response = $this->actingAs($donor, 'donor')->put(route('donor.profile.update'), [
            'first_name'  => 'New',
            'last_name'   => $donor->last_name,
            'email'       => $donor->email,
            'weight_kg'   => 70,
            'hemoglobin'  => 14,
            'blood_group' => $donor->blood_group,
        ]);

        $response->assertRedirect(route('donor.dashboard'));
        $this->assertDatabaseHas('donors', [
            'id'         => $donor->id,
            'first_name' => 'New',
            'weight_kg'  => 70,
        ]);
    }

    public function test_profile_update_validates_required_fields(): void
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->put(route('donor.profile.update'), [
            'first_name' => '',
            'last_name'  => $donor->last_name,
            'email'      => $donor->email,
        ]);

        $response->assertSessionHasErrors('first_name');
    }

    public function test_profile_update_rejects_email_taken_by_another_donor(): void
    {
        Donor::factory()->create(['email' => 'other@example.com']);
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->put(route('donor.profile.update'), [
            'first_name' => $donor->first_name,
            'last_name'  => $donor->last_name,
            'email'      => 'other@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_profile_update_keeps_own_email_valid(): void
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->put(route('donor.profile.update'), [
            'first_name' => $donor->first_name,
            'last_name'  => $donor->last_name,
            'email'      => $donor->email,
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_donor_can_upload_profile_image(): void
    {
        Storage::fake('public');
        $donor = Donor::factory()->create();
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($donor, 'donor')->put(route('donor.profile.update'), [
            'first_name'     => $donor->first_name,
            'last_name'      => $donor->last_name,
            'email'          => $donor->email,
            'profile_image'  => $file,
        ]);

        $response->assertRedirect(route('donor.dashboard'));
        $donor->refresh();
        $this->assertNotNull($donor->profile_image);
        Storage::disk('public')->assertExists($donor->profile_image);
    }

    public function test_profile_update_rejects_non_image_upload(): void
    {
        Storage::fake('public');
        $donor = Donor::factory()->create();
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($donor, 'donor')->put(route('donor.profile.update'), [
            'first_name'     => $donor->first_name,
            'last_name'      => $donor->last_name,
            'email'          => $donor->email,
            'profile_image'  => $file,
        ]);

        $response->assertSessionHasErrors('profile_image');
    }
}
