<?php

namespace Tests\Feature\Hospital;

use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HospitalRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_hospital_can_register_with_valid_data(): void
    {
        $response = $this->post(route('hospital.register'), [
            'name'                  => 'General Hospital Colombo',
            'email'                 => 'general@hospital.lk',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'registration_id'       => 'HOS-2026-0099',
            'phone'                 => '0112345678',
            'city'                  => 'Colombo',
            'district'              => 'Colombo',
            'address'               => '1 Hospital Road',
        ]);

        $response->assertRedirect(route('hospital.dashboard'));
        $this->assertDatabaseHas('hospitals', [
            'email'       => 'general@hospital.lk',
            'is_verified' => false,
        ]);
        $this->assertAuthenticated('hospital');
    }

    public function test_registration_requires_matching_password_confirmation(): void
    {
        $response = $this->post(route('hospital.register'), [
            'name'                  => 'General Hospital',
            'email'                 => 'general2@hospital.lk',
            'password'              => 'password123',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('hospitals', ['email' => 'general2@hospital.lk']);
    }

    public function test_registration_requires_unique_email(): void
    {
        Hospital::factory()->create(['email' => 'taken@hospital.lk']);

        $response = $this->post(route('hospital.register'), [
            'name'                  => 'General Hospital',
            'email'                 => 'taken@hospital.lk',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_requires_unique_registration_id(): void
    {
        Hospital::factory()->create(['registration_id' => 'HOS-DUPLICATE']);

        $response = $this->post(route('hospital.register'), [
            'name'                  => 'General Hospital',
            'email'                 => 'newhospital@hospital.lk',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'registration_id'       => 'HOS-DUPLICATE',
        ]);

        $response->assertSessionHasErrors('registration_id');
    }

    public function test_logged_in_hospital_is_redirected_away_from_the_registration_page(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->get(route('hospital.register'));

        // Laravel's default guest-middleware redirect target (same behaviour
        // already shared, untested, by the donor and admin guards) -- not
        // something specific to hospital registration to change here.
        $response->assertRedirect('/');
    }
}
