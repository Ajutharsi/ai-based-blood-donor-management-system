<?php

namespace Tests\Feature\Donor;

use App\Models\Donor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DonorRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            '*/predict'          => Http::response(['eligible' => true, 'confidence' => 92.5]),
            '*/predict-response' => Http::response(['response_probability' => 70, 'level' => 'high']),
            '*/detect-anomaly'   => Http::response(['is_anomaly' => false, 'anomaly_score' => 0.1]),
        ]);
    }

    public function test_donor_can_register_with_valid_data(): void
    {
        $response = $this->post(route('donor.register'), [
            'first_name'           => 'Kasun',
            'last_name'            => 'Perera',
            'email'                => 'kasun@example.com',
            'password'             => 'password123',
            'password_confirmation' => 'password123',
            'date_of_birth'        => '1995-05-01',
            'gender'               => 'Male',
            'blood_group'          => 'O+',
            'weight_kg'            => 65,
            'hemoglobin'           => 13.5,
            'city'                 => 'Colombo',
            'district'             => 'Colombo',
        ]);

        $response->assertRedirect(route('donor.dashboard'));
        $this->assertDatabaseHas('donors', [
            'email'       => 'kasun@example.com',
            'is_eligible' => true,
        ]);
        $this->assertAuthenticated('donor');
    }

    public function test_registration_requires_matching_password_confirmation(): void
    {
        $response = $this->post(route('donor.register'), [
            'first_name'           => 'Kasun',
            'last_name'            => 'Perera',
            'email'                => 'kasun2@example.com',
            'password'             => 'password123',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('donors', ['email' => 'kasun2@example.com']);
    }

    public function test_registration_requires_unique_email(): void
    {
        Donor::factory()->create(['email' => 'taken@example.com']);

        $response = $this->post(route('donor.register'), [
            'first_name'           => 'Kasun',
            'last_name'            => 'Perera',
            'email'                => 'taken@example.com',
            'password'             => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_rejects_invalid_medical_condition_value(): void
    {
        $response = $this->post(route('donor.register'), [
            'first_name'           => 'Kasun',
            'last_name'            => 'Perera',
            'email'                => 'kasun3@example.com',
            'password'             => 'password123',
            'password_confirmation' => 'password123',
            'medical_condition'    => 'Not A Real Option',
        ]);

        $response->assertSessionHasErrors('medical_condition');
    }
}
