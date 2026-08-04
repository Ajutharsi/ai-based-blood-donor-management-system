<?php

namespace Tests\Feature\Hospital;

use App\Mail\BloodRequestNotification;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DonorNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function makeRequest(Hospital $hospital, string $bloodGroup): BloodRequest
    {
        return BloodRequest::create([
            'hospital_id'  => $hospital->id,
            'blood_group'  => $bloodGroup,
            'units_needed' => 1,
            'urgency'      => 'standard',
            'status'       => 'pending',
        ]);
    }

    public function test_hospital_can_email_a_compatible_matched_donor(): void
    {
        Mail::fake();

        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create(['blood_group' => 'O-', 'is_eligible' => true]);
        $bloodRequest = $this->makeRequest($hospital, 'A+'); // O- is compatible with A+

        $response = $this->actingAs($hospital, 'hospital')
            ->post(route('hospital.requests.notify', [$bloodRequest->id, $donor->id]));

        $response->assertRedirect();
        Mail::assertSent(BloodRequestNotification::class, function ($mail) use ($donor) {
            return $mail->hasTo($donor->email);
        });
    }

    public function test_hospital_cannot_email_an_incompatible_donor(): void
    {
        Mail::fake();

        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create(['blood_group' => 'AB+', 'is_eligible' => true]);
        $bloodRequest = $this->makeRequest($hospital, 'O-'); // AB+ cannot donate to an O- request

        $response = $this->actingAs($hospital, 'hospital')
            ->post(route('hospital.requests.notify', [$bloodRequest->id, $donor->id]));

        $response->assertForbidden();
        Mail::assertNothingSent();
    }

    public function test_hospital_cannot_email_an_ineligible_donor(): void
    {
        Mail::fake();

        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create(['blood_group' => 'O-', 'is_eligible' => false]);
        $bloodRequest = $this->makeRequest($hospital, 'O-');

        $response = $this->actingAs($hospital, 'hospital')
            ->post(route('hospital.requests.notify', [$bloodRequest->id, $donor->id]));

        $response->assertForbidden();
        Mail::assertNothingSent();
    }

    public function test_hospital_cannot_notify_for_another_hospitals_request(): void
    {
        Mail::fake();

        $ownHospital = Hospital::factory()->create();
        $otherHospital = Hospital::factory()->create();
        $donor = Donor::factory()->create(['blood_group' => 'O-', 'is_eligible' => true]);
        $bloodRequest = $this->makeRequest($otherHospital, 'O-');

        $response = $this->actingAs($ownHospital, 'hospital')
            ->post(route('hospital.requests.notify', [$bloodRequest->id, $donor->id]));

        $response->assertForbidden();
        Mail::assertNothingSent();
    }
}
