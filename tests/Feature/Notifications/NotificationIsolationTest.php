<?php

namespace Tests\Feature\Notifications;

use App\Models\Admin;
use App\Models\Donor;
use App\Models\Hospital;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationIsolationTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): Admin
    {
        return Admin::create([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_donor_guard_cannot_access_hospital_notifications_route(): void
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->get(route('hospital.notifications.index'));

        $response->assertRedirect(route('hospital.login'));
    }

    public function test_hospital_guard_cannot_access_donor_notifications_route(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->get(route('donor.notifications.index'));

        $response->assertRedirect(route('donor.login'));
    }

    public function test_donor_guard_cannot_access_admin_notifications_route(): void
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->get(route('admin.notifications.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_donor_cannot_mark_a_hospitals_notification_as_read_via_donor_route(): void
    {
        $donor = Donor::factory()->create();
        $hospital = Hospital::factory()->create();
        $hospitalNotification = Notification::factory()->forHospital($hospital->id)->unread()->create();

        // Even if a donor somehow guesses a hospital notification's ID, the
        // donor route's ownership check must reject it -- user_type mismatch,
        // not just user_id mismatch.
        $response = $this->actingAs($donor, 'donor')
            ->post(route('donor.notifications.read', $hospitalNotification->id));

        $response->assertForbidden();
        $this->assertNull($hospitalNotification->fresh()->read_at);
    }

    public function test_hospital_cannot_mark_a_donors_notification_as_read_via_hospital_route(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create();
        $donorNotification = Notification::factory()->forDonor($donor->id)->unread()->create();

        $response = $this->actingAs($hospital, 'hospital')
            ->post(route('hospital.notifications.read', $donorNotification->id));

        $response->assertForbidden();
        $this->assertNull($donorNotification->fresh()->read_at);
    }

    public function test_admin_cannot_mark_a_donors_notification_as_read_via_admin_route(): void
    {
        $admin = $this->makeAdmin();
        $donor = Donor::factory()->create();
        $donorNotification = Notification::factory()->forDonor($donor->id)->unread()->create();

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.notifications.read', $donorNotification->id));

        $response->assertForbidden();
        $this->assertNull($donorNotification->fresh()->read_at);
    }

    public function test_donors_notification_list_never_includes_hospital_or_admin_rows(): void
    {
        $donor = Donor::factory()->create();
        $hospital = Hospital::factory()->create();
        $admin = $this->makeAdmin();

        Notification::factory()->forDonor($donor->id)->create(['title' => 'Mine']);
        Notification::factory()->forHospital($hospital->id)->create(['title' => 'Not mine (hospital)']);
        Notification::factory()->forAdmin($admin->id)->create(['title' => 'Not mine (admin)']);

        $response = $this->actingAs($donor, 'donor')->get(route('donor.notifications.index'));

        $response->assertOk();
        $response->assertSee('Mine');
        $response->assertDontSee('Not mine (hospital)');
        $response->assertDontSee('Not mine (admin)');
    }

    public function test_mark_all_read_only_touches_the_authenticated_users_own_rows(): void
    {
        $donorA = Donor::factory()->create();
        $donorB = Donor::factory()->create();
        Notification::factory()->forDonor($donorA->id)->unread()->create();
        $bNotification = Notification::factory()->forDonor($donorB->id)->unread()->create();

        $this->actingAs($donorA, 'donor')->post(route('donor.notifications.readAll'));

        $this->assertEquals(0, $donorA->fresh()->unreadNotificationsCount());
        $this->assertNull($bNotification->fresh()->read_at);
    }
}
