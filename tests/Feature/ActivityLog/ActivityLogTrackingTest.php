<?php

namespace Tests\Feature\ActivityLog;

use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\Appointment;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\DonorResponse;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ActivityLogTrackingTest extends TestCase
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

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            '*/predict'          => Http::response(['eligible' => true, 'confidence' => 92.5]),
            '*/predict-response' => Http::response(['response_probability' => 70, 'level' => 'high']),
            '*/detect-anomaly'   => Http::response(['is_anomaly' => false, 'anomaly_score' => 0.1]),
        ]);
    }

    // ── AUTH ─────────────────────────────────────────────

    public function test_donor_login_is_logged(): void
    {
        $donor = Donor::factory()->create(['email' => 'donor@example.com']);

        $this->post(route('donor.login'), ['email' => 'donor@example.com', 'password' => 'password']);

        $this->assertEquals(1, ActivityLog::where('actor_type', 'donor')->where('actor_id', $donor->id)
            ->where('category', 'auth')->where('action', 'login')->count());
    }

    public function test_hospital_login_is_logged(): void
    {
        $hospital = Hospital::factory()->create(['email' => 'hospital@example.com']);

        $this->post(route('hospital.login'), ['email' => 'hospital@example.com', 'password' => 'password']);

        $this->assertEquals(1, ActivityLog::where('actor_type', 'hospital')->where('actor_id', $hospital->id)
            ->where('category', 'auth')->where('action', 'login')->count());
    }

    public function test_admin_login_is_logged(): void
    {
        $admin = $this->makeAdmin();

        $this->post(route('admin.login'), ['email' => 'admin@example.com', 'password' => 'password']);

        $this->assertEquals(1, ActivityLog::where('actor_type', 'admin')->where('actor_id', $admin->id)
            ->where('category', 'auth')->where('action', 'login')->count());
    }

    public function test_logout_is_logged(): void
    {
        $donor = Donor::factory()->create();

        $this->actingAs($donor, 'donor')->post(route('donor.logout'));

        $this->assertEquals(1, ActivityLog::where('actor_type', 'donor')->where('actor_id', $donor->id)
            ->where('category', 'auth')->where('action', 'logout')->count());
    }

    // ── REGISTRATION ─────────────────────────────────────

    public function test_donor_registration_is_logged(): void
    {
        $this->post(route('donor.register'), [
            'first_name'            => 'Kasun',
            'last_name'             => 'Perera',
            'email'                 => 'kasun@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'date_of_birth'         => '1995-05-01',
            'gender'                => 'Male',
            'blood_group'           => 'O+',
            'city'                  => 'Colombo',
            'district'              => 'Colombo',
        ]);

        $donor = Donor::where('email', 'kasun@example.com')->firstOrFail();

        $this->assertEquals(1, ActivityLog::where('actor_type', 'donor')->where('actor_id', $donor->id)
            ->where('category', 'registration')->where('action', 'donor_registered')->count());
    }

    public function test_hospital_registration_is_logged(): void
    {
        $this->post(route('hospital.register'), [
            'name'                  => 'General Hospital Colombo',
            'email'                 => 'general@hospital.lk',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'registration_id'       => 'HOS-2026-0099',
        ]);

        $hospital = Hospital::where('email', 'general@hospital.lk')->firstOrFail();

        $this->assertEquals(1, ActivityLog::where('actor_type', 'hospital')->where('actor_id', $hospital->id)
            ->where('category', 'registration')->where('action', 'hospital_registered')->count());
    }

    // ── PROFILE UPDATES ──────────────────────────────────

    public function test_donor_profile_update_is_logged(): void
    {
        $donor = Donor::factory()->create();

        $this->actingAs($donor, 'donor')->put(route('donor.profile.update'), [
            'first_name'  => 'Updated',
            'last_name'   => $donor->last_name,
            'email'       => $donor->email,
            'blood_group' => $donor->blood_group,
            'gender'      => $donor->gender,
        ]);

        $this->assertEquals(1, ActivityLog::where('actor_type', 'donor')->where('actor_id', $donor->id)
            ->where('category', 'profile')->where('action', 'profile_updated')->count());
    }

    public function test_hospital_profile_update_is_logged(): void
    {
        $hospital = Hospital::factory()->create();

        $this->actingAs($hospital, 'hospital')->put(route('hospital.profile.update'), [
            'name'  => 'Updated Hospital',
            'email' => $hospital->email,
        ]);

        $this->assertEquals(1, ActivityLog::where('actor_type', 'hospital')->where('actor_id', $hospital->id)
            ->where('category', 'profile')->where('action', 'profile_updated')->count());
    }

    public function test_admin_profile_update_is_logged(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin, 'admin')->put(route('admin.profile.update'), [
            'name'  => 'Updated Admin',
            'email' => $admin->email,
        ]);

        $this->assertEquals(1, ActivityLog::where('actor_type', 'admin')->where('actor_id', $admin->id)
            ->where('category', 'profile')->where('action', 'profile_updated')->count());
    }

    // ── BLOOD REQUESTS ───────────────────────────────────

    public function test_blood_request_creation_is_logged(): void
    {
        $hospital = Hospital::factory()->create();

        $this->actingAs($hospital, 'hospital')->post(route('hospital.request.store'), [
            'blood_group'  => 'O+',
            'units_needed' => 3,
            'urgency'      => 'standard',
        ]);

        $this->assertEquals(1, ActivityLog::where('actor_type', 'hospital')->where('actor_id', $hospital->id)
            ->where('category', 'blood_request')->where('action', 'blood_request_created')->count());
    }

    public function test_blood_request_fulfillment_is_logged(): void
    {
        $hospital = Hospital::factory()->create();
        $bloodRequest = BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'O+', 'units_needed' => 2, 'status' => 'pending']);

        $this->actingAs($hospital, 'hospital')->post(route('hospital.requests.fulfill', $bloodRequest));

        $this->assertEquals(1, ActivityLog::where('actor_type', 'hospital')->where('actor_id', $hospital->id)
            ->where('category', 'blood_request')->where('action', 'blood_request_fulfilled')
            ->where('subject_id', $bloodRequest->id)->count());
    }

    public function test_donor_response_is_logged(): void
    {
        $donor = Donor::factory()->eligible()->bloodGroup('O+')->create();
        $hospital = Hospital::factory()->create();
        $bloodRequest = BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'O+', 'units_needed' => 2, 'status' => 'pending']);

        $this->actingAs($donor, 'donor')->post(route('donor.requests.respond', $bloodRequest), ['status' => 'available']);

        $this->assertEquals(1, ActivityLog::where('actor_type', 'donor')->where('actor_id', $donor->id)
            ->where('category', 'blood_request')->where('action', 'blood_request_responded')->count());
    }

    // ── NOTIFICATIONS ────────────────────────────────────

    public function test_sending_a_notification_is_logged(): void
    {
        $hospital = Hospital::factory()->create();

        $this->post(route('hospital.register'), [
            'name'                  => 'Notify Hospital',
            'email'                 => 'notify@hospital.lk',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'registration_id'       => 'HOS-NOTIFY-1',
        ]);

        // Hospital registration itself broadcasts a notification to all admins.
        $this->assertGreaterThanOrEqual(1, ActivityLog::where('category', 'notification')
            ->where('action', 'notification_sent')->count());
    }

    // ── BLOOD INVENTORY ──────────────────────────────────

    public function test_inventory_adjustment_is_logged(): void
    {
        $hospital = Hospital::factory()->create();
        $item = BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->create(['available_units' => 10, 'minimum_threshold' => 5]);

        $this->actingAs($hospital, 'hospital')->post(route('hospital.inventory.add', $item->id), ['units' => 5]);

        $this->assertEquals(1, ActivityLog::where('actor_type', 'hospital')->where('actor_id', $hospital->id)
            ->where('category', 'inventory')->where('action', 'inventory_adjusted')
            ->where('subject_id', $item->id)->count());
    }

    public function test_inventory_threshold_update_is_logged(): void
    {
        $hospital = Hospital::factory()->create();
        $item = BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->create(['available_units' => 10, 'minimum_threshold' => 5]);

        $this->actingAs($hospital, 'hospital')->post(route('hospital.inventory.threshold', $item->id), ['minimum_threshold' => 15]);

        $this->assertEquals(1, ActivityLog::where('actor_type', 'hospital')->where('actor_id', $hospital->id)
            ->where('category', 'inventory')->where('action', 'inventory_threshold_updated')->count());
    }

    // ── APPOINTMENTS + DONATIONS ─────────────────────────

    public function test_appointment_lifecycle_actions_are_logged(): void
    {
        $donor = Donor::factory()->eligible()->bloodGroup('O+')->create();
        $hospital = Hospital::factory()->create();
        $bloodRequest = BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'O+', 'units_needed' => 2, 'status' => 'pending']);
        DonorResponse::create(['donor_id' => $donor->id, 'blood_request_id' => $bloodRequest->id, 'status' => 'available', 'responded_at' => now()]);

        $this->actingAs($donor, 'donor')->post(route('donor.appointments.store', $bloodRequest), [
            'appointment_date' => now()->addDays(3)->format('Y-m-d'),
            'appointment_time' => '10:00',
        ]);
        $appointment = Appointment::where('donor_id', $donor->id)->firstOrFail();

        $this->assertEquals(1, ActivityLog::where('category', 'appointment')->where('action', 'appointment_booked')
            ->where('subject_id', $appointment->id)->count());

        $this->actingAs($hospital, 'hospital')->post(route('hospital.appointments.approve', $appointment));
        $this->assertEquals(1, ActivityLog::where('category', 'appointment')->where('action', 'appointment_approved')
            ->where('subject_id', $appointment->id)->count());

        $this->actingAs($hospital, 'hospital')->post(route('hospital.appointments.reschedule', $appointment), [
            'appointment_date' => now()->addDays(5)->format('Y-m-d'),
            'appointment_time' => '11:00',
        ]);
        $this->assertEquals(1, ActivityLog::where('category', 'appointment')->where('action', 'appointment_rescheduled')
            ->where('subject_id', $appointment->id)->count());

        $this->actingAs($hospital, 'hospital')->post(route('hospital.appointments.complete', $appointment));
        $this->assertEquals(1, ActivityLog::where('category', 'appointment')->where('action', 'appointment_completed')
            ->where('subject_id', $appointment->id)->count());

        // Completion also records a donation, tracked under its own category.
        $this->assertEquals(1, ActivityLog::where('category', 'donation')->where('action', 'donation_recorded')->count());
    }

    public function test_appointment_rejection_is_logged(): void
    {
        $donor = Donor::factory()->eligible()->bloodGroup('O+')->create();
        $hospital = Hospital::factory()->create();
        $bloodRequest = BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'O+', 'units_needed' => 2, 'status' => 'pending']);
        $appointment = Appointment::factory()->create(['donor_id' => $donor->id, 'blood_request_id' => $bloodRequest->id, 'hospital_id' => $hospital->id, 'status' => 'pending']);

        $this->actingAs($hospital, 'hospital')->post(route('hospital.appointments.reject', $appointment), ['reason' => 'No slots']);

        $this->assertEquals(1, ActivityLog::where('category', 'appointment')->where('action', 'appointment_rejected')
            ->where('subject_id', $appointment->id)->count());
    }

    public function test_appointment_cancellation_is_logged(): void
    {
        $donor = Donor::factory()->create();
        $hospital = Hospital::factory()->create();
        $bloodRequest = BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'O+', 'units_needed' => 2, 'status' => 'pending']);
        $appointment = Appointment::factory()->create(['donor_id' => $donor->id, 'blood_request_id' => $bloodRequest->id, 'hospital_id' => $hospital->id, 'status' => 'pending']);

        $this->actingAs($donor, 'donor')->post(route('donor.appointments.cancel', $appointment));

        $this->assertEquals(1, ActivityLog::where('category', 'appointment')->where('action', 'appointment_cancelled')
            ->where('subject_id', $appointment->id)->count());
    }

    // ── AI PREDICTIONS ───────────────────────────────────

    public function test_ai_prediction_is_logged_on_registration(): void
    {
        $this->post(route('donor.register'), [
            'first_name'            => 'Ai',
            'last_name'             => 'Test',
            'email'                 => 'aitest@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'date_of_birth'         => '1995-05-01',
            'gender'                => 'Male',
            'blood_group'           => 'O+',
        ]);

        $this->assertGreaterThanOrEqual(3, ActivityLog::where('category', 'ai_prediction')
            ->where('action', 'ai_prediction_logged')->count());
    }

    // ── ADMIN ACTIONS ────────────────────────────────────

    public function test_admin_toggling_donor_eligibility_is_logged(): void
    {
        $admin = $this->makeAdmin();
        $donor = Donor::factory()->notEligible()->create();

        $this->actingAs($admin, 'admin')->post(route('admin.donors.toggle', $donor));

        $this->assertEquals(1, ActivityLog::where('actor_type', 'admin')->where('actor_id', $admin->id)
            ->where('category', 'admin')->where('action', 'donor_eligibility_toggled')->count());
    }

    public function test_admin_toggling_hospital_verification_is_logged(): void
    {
        $admin = $this->makeAdmin();
        $hospital = Hospital::factory()->unverified()->create();

        $this->actingAs($admin, 'admin')->post(route('admin.hospitals.toggle', $hospital));

        $this->assertEquals(1, ActivityLog::where('actor_type', 'admin')->where('actor_id', $admin->id)
            ->where('category', 'admin')->where('action', 'hospital_verification_toggled')->count());
    }

    public function test_admin_deleting_a_donor_is_logged(): void
    {
        $admin = $this->makeAdmin();
        $donor = Donor::factory()->create();
        $donorId = $donor->id;

        $this->actingAs($admin, 'admin')->delete(route('admin.donors.destroy', $donor));

        $this->assertEquals(1, ActivityLog::where('actor_type', 'admin')->where('actor_id', $admin->id)
            ->where('category', 'admin')->where('action', 'donor_deleted')
            ->where('subject_id', $donorId)->count());
    }

    public function test_admin_recording_a_donation_is_logged(): void
    {
        $admin = $this->makeAdmin();
        $donor = Donor::factory()->create();

        $this->actingAs($admin, 'admin')->post(route('admin.donors.donations.store', $donor), [
            'donation_date' => now()->format('Y-m-d'),
        ]);

        $this->assertEquals(1, ActivityLog::where('actor_type', 'admin')->where('actor_id', $admin->id)
            ->where('category', 'donation')->where('action', 'donation_recorded')->count());
    }
}
