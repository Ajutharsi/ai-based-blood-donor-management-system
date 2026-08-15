<?php

namespace Tests\Feature\Donor;

use App\Models\Donor;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_donor_notifications(): void
    {
        $this->get(route('donor.notifications.index'))->assertRedirect(route('donor.login'));
    }

    public function test_donor_can_view_their_notifications_list(): void
    {
        $donor = Donor::factory()->create();
        Notification::factory()->forDonor($donor->id)->create(['title' => 'Eligible for a request']);

        $response = $this->actingAs($donor, 'donor')->get(route('donor.notifications.index'));

        $response->assertOk();
        $response->assertSee('Eligible for a request');
    }

    public function test_unread_notification_count_is_correct(): void
    {
        $donor = Donor::factory()->create();
        Notification::factory()->forDonor($donor->id)->unread()->count(2)->create();
        Notification::factory()->forDonor($donor->id)->read()->create();

        $this->assertEquals(2, $donor->fresh()->unreadNotificationsCount());
    }

    public function test_donor_can_mark_their_own_notification_as_read(): void
    {
        $donor = Donor::factory()->create();
        $notification = Notification::factory()->forDonor($donor->id)->unread()->create();

        $response = $this->actingAs($donor, 'donor')->post(route('donor.notifications.read', $notification->id));

        $response->assertRedirect();
        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_donor_cannot_mark_another_donors_notification_as_read(): void
    {
        $owner = Donor::factory()->create();
        $intruder = Donor::factory()->create();
        $notification = Notification::factory()->forDonor($owner->id)->unread()->create();

        $response = $this->actingAs($intruder, 'donor')->post(route('donor.notifications.read', $notification->id));

        $response->assertForbidden();
        $this->assertNull($notification->fresh()->read_at);
    }

    public function test_donor_can_mark_all_notifications_as_read(): void
    {
        $donor = Donor::factory()->create();
        Notification::factory()->forDonor($donor->id)->unread()->count(3)->create();

        $this->actingAs($donor, 'donor')->post(route('donor.notifications.readAll'));

        $this->assertEquals(0, $donor->fresh()->unreadNotificationsCount());
    }

    public function test_unread_filter_only_shows_unread_notifications(): void
    {
        $donor = Donor::factory()->create();
        Notification::factory()->forDonor($donor->id)->unread()->create(['title' => 'Still unread']);
        Notification::factory()->forDonor($donor->id)->read()->create(['title' => 'Already read']);

        $response = $this->actingAs($donor, 'donor')
            ->get(route('donor.notifications.index', ['status' => 'unread']));

        $response->assertOk();
        $response->assertSee('Still unread');
        $response->assertDontSee('Already read');
    }

    public function test_dashboard_shows_unread_notification_badge(): void
    {
        $donor = Donor::factory()->create();
        Notification::factory()->forDonor($donor->id)->unread()->create(['title' => 'You are a match']);

        $response = $this->actingAs($donor, 'donor')->get(route('donor.dashboard'));

        $response->assertOk();
        $response->assertSee('You are a match');
    }
}
