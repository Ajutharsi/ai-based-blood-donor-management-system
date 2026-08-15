<?php

namespace Tests\Feature\Security;

use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class SecurityLoggingTest extends TestCase
{
    use RefreshDatabase;

    public function test_failed_donor_login_is_logged(): void
    {
        Log::spy();
        $donor = Donor::factory()->create();

        $this->post(route('donor.login'), ['email' => $donor->email, 'password' => 'wrong']);

        Log::shouldHaveReceived('warning')
            ->with('Failed donor login attempt', \Mockery::on(fn ($ctx) => $ctx['email'] === $donor->email))
            ->once();
    }

    public function test_failed_hospital_login_is_logged(): void
    {
        Log::spy();
        $hospital = Hospital::factory()->create();

        $this->post(route('hospital.login'), ['email' => $hospital->email, 'password' => 'wrong']);

        Log::shouldHaveReceived('warning')->with('Failed hospital login attempt', \Mockery::any())->once();
    }

    public function test_unauthorized_cross_hospital_access_is_logged(): void
    {
        Log::spy();
        $hospitalA = Hospital::factory()->create();
        $hospitalB = Hospital::factory()->create();
        $bloodRequest = \App\Models\BloodRequest::create([
            'hospital_id' => $hospitalA->id, 'blood_group' => 'O+', 'units_needed' => 1, 'status' => 'pending',
        ]);

        $this->actingAs($hospitalB, 'hospital')->get(route('hospital.requests.show', $bloodRequest));

        Log::shouldHaveReceived('warning')
            ->with('Unauthorized access attempt', \Mockery::on(fn ($ctx) => $ctx['status'] === 403))
            ->once();
    }
}
