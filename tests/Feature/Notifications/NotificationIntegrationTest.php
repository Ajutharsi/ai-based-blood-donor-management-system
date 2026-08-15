<?php

namespace Tests\Feature\Notifications;

use App\Models\Admin;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\DonorResponse;
use App\Models\Hospital;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotificationIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(array $overrides = []): Admin
    {
        return Admin::create(array_merge([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
        ], $overrides));
    }

    public function test_creating_a_critical_request_notifies_every_admin(): void
    {
        $admin1 = $this->makeAdmin(['email' => 'a1@example.com']);
        $admin2 = $this->makeAdmin(['email' => 'a2@example.com']);
        $hospital = Hospital::factory()->create(['name' => 'City Hospital']);

        $this->actingAs($hospital, 'hospital')->post(route('hospital.request.store'), [
            'blood_group'  => 'O+',
            'units_needed' => 3,
            'urgency'      => 'critical',
        ]);

        $this->assertEquals(1, Notification::where('user_type', 'admin')
            ->where('user_id', $admin1->id)->where('type', 'shortage_alert')->count());
        $this->assertEquals(1, Notification::where('user_type', 'admin')
            ->where('user_id', $admin2->id)->where('type', 'shortage_alert')->count());
    }

    public function test_creating_a_standard_request_does_not_notify_admins(): void
    {
        $this->makeAdmin();
        $hospital = Hospital::factory()->create();

        $this->actingAs($hospital, 'hospital')->post(route('hospital.request.store'), [
            'blood_group'  => 'O+',
            'units_needed' => 1,
            'urgency'      => 'standard',
        ]);

        $this->assertEquals(0, Notification::where('type', 'shortage_alert')->count());
    }

    public function test_creating_a_request_notifies_top_matched_donors(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create(['blood_group' => 'O+', 'is_eligible' => true, 'ai_confidence' => 90]);

        $this->actingAs($hospital, 'hospital')->post(route('hospital.request.store'), [
            'blood_group'  => 'O+',
            'units_needed' => 1,
            'urgency'      => 'urgent',
        ]);

        $notification = Notification::where('user_type', 'donor')
            ->where('user_id', $donor->id)->where('type', 'donor_match')->first();

        $this->assertNotNull($notification);
        $this->assertStringContainsString('eligible for a blood donation request', $notification->message);
    }

    public function test_notify_donor_button_creates_an_in_app_notification_alongside_the_email(): void
    {
        Mail::fake();

        $hospital = Hospital::factory()->create(['name' => 'City Hospital']);
        $donor = Donor::factory()->create(['blood_group' => 'O+', 'is_eligible' => true]);
        $bloodRequest = BloodRequest::create([
            'hospital_id'  => $hospital->id,
            'blood_group'  => 'O+',
            'units_needed' => 1,
        ]);

        $this->actingAs($hospital, 'hospital')
            ->post(route('hospital.requests.notify', [$bloodRequest->id, $donor->id]));

        $notification = Notification::where('user_type', 'donor')
            ->where('user_id', $donor->id)->where('type', 'blood_request')->first();

        $this->assertNotNull($notification);
        $this->assertEquals("Emergency blood request: O+ needed at City Hospital", $notification->message);
        $this->assertEquals($bloodRequest->id, $notification->related_id);
    }

    public function test_donor_response_notifies_the_owning_hospital(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create(['blood_group' => 'O+', 'is_eligible' => true, 'first_name' => 'Kasun', 'last_name' => 'Perera']);
        $bloodRequest = BloodRequest::create([
            'hospital_id'  => $hospital->id,
            'blood_group'  => 'O+',
            'units_needed' => 1,
        ]);

        $this->actingAs($donor, 'donor')->post(route('donor.requests.respond', $bloodRequest), [
            'status' => 'available',
        ]);

        $notification = Notification::where('user_type', 'hospital')
            ->where('user_id', $hospital->id)->where('type', 'donor_response')->first();

        $this->assertNotNull($notification);
        $this->assertStringContainsString('Kasun Perera', $notification->message);
        $this->assertStringContainsString('available', $notification->message);
        $this->assertEquals($bloodRequest->id, $notification->related_id);
    }

    public function test_fulfilling_a_request_notifies_donors_who_responded_available(): void
    {
        $hospital = Hospital::factory()->create(['name' => 'City Hospital']);
        $available = Donor::factory()->create();
        $notAvailable = Donor::factory()->create();
        $bloodRequest = BloodRequest::create([
            'hospital_id'  => $hospital->id,
            'blood_group'  => 'O+',
            'units_needed' => 1,
        ]);
        DonorResponse::create(['donor_id' => $available->id, 'blood_request_id' => $bloodRequest->id, 'status' => 'available', 'responded_at' => now()]);
        DonorResponse::create(['donor_id' => $notAvailable->id, 'blood_request_id' => $bloodRequest->id, 'status' => 'not_available', 'responded_at' => now()]);

        $this->actingAs($hospital, 'hospital')->post(route('hospital.requests.fulfill', $bloodRequest));

        $this->assertEquals(1, Notification::where('user_type', 'donor')
            ->where('user_id', $available->id)->where('type', 'fulfillment')->count());
        $this->assertEquals(0, Notification::where('user_type', 'donor')
            ->where('user_id', $notAvailable->id)->where('type', 'fulfillment')->count());
    }

    public function test_new_hospital_registration_notifies_every_admin(): void
    {
        $admin = $this->makeAdmin();

        $this->post(route('hospital.register'), [
            'name'                  => 'New General Hospital',
            'email'                 => 'newhospital@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'district'              => 'Kandy',
        ]);

        $notification = Notification::where('user_type', 'admin')
            ->where('user_id', $admin->id)->where('type', 'hospital_registration')->first();

        $this->assertNotNull($notification);
        $this->assertStringContainsString('New General Hospital', $notification->message);
    }

    public function test_anomalous_donor_registration_notifies_every_admin(): void
    {
        $admin = $this->makeAdmin();

        Http::fake([
            '*/predict'          => Http::response(['eligible' => true, 'confidence' => 80, 'model' => 'k-NN', 'status' => 'success']),
            '*/predict-response' => Http::response(['response_probability' => 60, 'level' => 'medium', 'model' => 'Random Forest', 'status' => 'success']),
            '*/detect-anomaly'   => Http::response(['is_anomaly' => true, 'anomaly_score' => 91.2, 'model' => 'Isolation Forest', 'status' => 'success']),
        ]);

        $this->post(route('donor.register'), [
            'first_name'            => 'Anomalous',
            'last_name'             => 'Donor',
            'email'                 => 'anomalous@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'blood_group'           => 'O+',
            'weight_kg'             => 65,
            'hemoglobin'            => 13.5,
        ]);

        $notification = Notification::where('user_type', 'admin')
            ->where('user_id', $admin->id)->where('type', 'ai_alert')->first();

        $this->assertNotNull($notification);
        $this->assertStringContainsString('Anomalous Donor', $notification->message);
    }

    public function test_non_anomalous_donor_registration_does_not_notify_admins(): void
    {
        $this->makeAdmin();

        Http::fake([
            '*/predict'          => Http::response(['eligible' => true, 'confidence' => 80, 'model' => 'k-NN', 'status' => 'success']),
            '*/predict-response' => Http::response(['response_probability' => 60, 'level' => 'medium', 'model' => 'Random Forest', 'status' => 'success']),
            '*/detect-anomaly'   => Http::response(['is_anomaly' => false, 'anomaly_score' => 4.0, 'model' => 'Isolation Forest', 'status' => 'success']),
        ]);

        $this->post(route('donor.register'), [
            'first_name'            => 'Normal',
            'last_name'             => 'Donor',
            'email'                 => 'normal@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'blood_group'           => 'O+',
            'weight_kg'             => 65,
            'hemoglobin'            => 13.5,
        ]);

        $this->assertEquals(0, Notification::where('type', 'ai_alert')->count());
    }
}
