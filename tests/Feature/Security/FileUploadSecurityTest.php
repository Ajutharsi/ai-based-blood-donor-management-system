<?php

namespace Tests\Feature\Security;

use App\Models\Donor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Http::fake([
            '*/predict'          => Http::response(['eligible' => true, 'confidence' => 92.5]),
            '*/predict-response' => Http::response(['response_probability' => 70, 'level' => 'high']),
            '*/detect-anomaly'   => Http::response(['is_anomaly' => false, 'anomaly_score' => 0.1]),
        ]);
    }

    public function test_profile_image_rejects_a_file_whose_declared_mime_type_is_not_an_image(): void
    {
        $donor = Donor::factory()->create();

        // Named with an image extension but declares a non-image MIME type
        // -- the `image`/`mimes` rules must key off real content type, not
        // just the filename's extension.
        $file = UploadedFile::fake()->create('shell.png', 10, 'application/x-httpd-php');

        $response = $this->actingAs($donor, 'donor')->put(route('donor.profile.update'), [
            'first_name'  => $donor->first_name,
            'last_name'   => $donor->last_name,
            'email'       => $donor->email,
            'blood_group' => $donor->blood_group,
            'profile_image' => $file,
        ]);

        $response->assertSessionHasErrors('profile_image');
    }

    public function test_profile_image_rejects_a_disallowed_extension(): void
    {
        $donor = Donor::factory()->create();
        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->actingAs($donor, 'donor')->put(route('donor.profile.update'), [
            'first_name'  => $donor->first_name,
            'last_name'   => $donor->last_name,
            'email'       => $donor->email,
            'blood_group' => $donor->blood_group,
            'profile_image' => $file,
        ]);

        $response->assertSessionHasErrors('profile_image');
    }

    public function test_profile_image_rejects_svg_to_close_off_svg_based_xss(): void
    {
        $donor = Donor::factory()->create();
        $file = UploadedFile::fake()->create('avatar.svg', 10, 'image/svg+xml');

        $response = $this->actingAs($donor, 'donor')->put(route('donor.profile.update'), [
            'first_name'  => $donor->first_name,
            'last_name'   => $donor->last_name,
            'email'       => $donor->email,
            'blood_group' => $donor->blood_group,
            'profile_image' => $file,
        ]);

        $response->assertSessionHasErrors('profile_image');
    }

    public function test_profile_image_rejects_a_file_over_the_size_limit(): void
    {
        $donor = Donor::factory()->create();
        $file = UploadedFile::fake()->image('big.jpg')->size(3000); // > 2048 KB limit

        $response = $this->actingAs($donor, 'donor')->put(route('donor.profile.update'), [
            'first_name'  => $donor->first_name,
            'last_name'   => $donor->last_name,
            'email'       => $donor->email,
            'blood_group' => $donor->blood_group,
            'profile_image' => $file,
        ]);

        $response->assertSessionHasErrors('profile_image');
    }

    public function test_valid_profile_image_is_accepted_and_stored_with_a_random_filename(): void
    {
        $donor = Donor::factory()->create();
        $file = UploadedFile::fake()->image('me.jpg')->size(500);

        $response = $this->actingAs($donor, 'donor')->put(route('donor.profile.update'), [
            'first_name'  => $donor->first_name,
            'last_name'   => $donor->last_name,
            'email'       => $donor->email,
            'blood_group' => $donor->blood_group,
            'profile_image' => $file,
        ]);

        $response->assertSessionHasNoErrors();
        $storedPath = $donor->fresh()->profile_image;
        $this->assertNotNull($storedPath);
        // The original filename ("me.jpg") must not survive into storage --
        // Laravel's ->store() generates a random name.
        $this->assertStringNotContainsString('me.jpg', $storedPath);
        Storage::disk('public')->assertExists($storedPath);
    }
}
