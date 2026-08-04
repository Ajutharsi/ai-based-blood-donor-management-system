<?php

namespace Tests\Feature\Hospital;

use App\Models\Hospital;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_hospital_notifications(): void
    {
        $this->get(route('hospital.notifications.index'))->assertRedirect(route('hospital.login'));
    }

    public function test_hospital_can_view_their_notifications_list(): void
    {
        $hospital = Hospital::factory()->create();
        Notification::factory()->forHospital($hospital->id)->create(['title' => 'Donor responded']);

        $response = $this->actingAs($hospital, 'hospital')->get(route('hospital.notifications.index'));

        $response->assertOk();
        $response->assertSee('Donor responded');
    }

    public function test_unread_notification_count_is_correct(): void
    {
        $hospital = Hospital::factory()->create();
        Notification::factory()->forHospital($hospital->id)->unread()->count(3)->create();
        Notification::factory()->forHospital($hospital->id)->read()->create();

        $this->assertEquals(3, $hospital->fresh()->unreadNotificationsCount());
    }

    public function test_hospital_can_mark_their_own_notification_as_read(): void
    {
        $hospital = Hospital::factory()->create();
        $notification = Notification::factory()->forHospital($hospital->id)->unread()->create();

        $response = $this->actingAs($hospital, 'hospital')->post(route('hospital.notifications.read', $notification->id));

        $response->assertRedirect();
        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_hospital_cannot_mark_another_hospitals_notification_as_read(): void
    {
        $owner = Hospital::factory()->create();
        $intruder = Hospital::factory()->create();
        $notification = Notification::factory()->forHospital($owner->id)->unread()->create();

        $response = $this->actingAs($intruder, 'hospital')->post(route('hospital.notifications.read', $notification->id));

        $response->assertForbidden();
        $this->assertNull($notification->fresh()->read_at);
    }

    public function test_hospital_can_mark_all_notifications_as_read(): void
    {
        $hospital = Hospital::factory()->create();
        Notification::factory()->forHospital($hospital->id)->unread()->count(4)->create();

        $this->actingAs($hospital, 'hospital')->post(route('hospital.notifications.readAll'));

        $this->assertEquals(0, $hospital->fresh()->unreadNotificationsCount());
    }

    public function test_unread_filter_only_shows_unread_notifications(): void
    {
        $hospital = Hospital::factory()->create();
        Notification::factory()->forHospital($hospital->id)->unread()->create(['title' => 'Fresh alert']);
        Notification::factory()->forHospital($hospital->id)->read()->create(['title' => 'Old alert']);

        $response = $this->actingAs($hospital, 'hospital')
            ->get(route('hospital.notifications.index', ['status' => 'unread']));

        $response->assertOk();
        $response->assertSee('Fresh alert');
        $response->assertDontSee('Old alert');
    }
}
