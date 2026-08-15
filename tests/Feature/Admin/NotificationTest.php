<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
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

    public function test_guest_cannot_access_admin_notifications(): void
    {
        $this->get(route('admin.notifications.index'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_their_notifications_list(): void
    {
        $admin = $this->makeAdmin();
        Notification::factory()->forAdmin($admin->id)->create(['title' => 'New hospital registration']);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.notifications.index'));

        $response->assertOk();
        $response->assertSee('New hospital registration');
    }

    public function test_unread_notification_count_is_correct(): void
    {
        $admin = $this->makeAdmin();
        Notification::factory()->forAdmin($admin->id)->unread()->count(5)->create();
        Notification::factory()->forAdmin($admin->id)->read()->create();

        $this->assertEquals(5, $admin->fresh()->unreadNotificationsCount());
    }

    public function test_admin_can_mark_their_own_notification_as_read(): void
    {
        $admin = $this->makeAdmin();
        $notification = Notification::factory()->forAdmin($admin->id)->unread()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.notifications.read', $notification->id));

        $response->assertRedirect();
        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_admin_cannot_mark_another_admins_notification_as_read(): void
    {
        $owner = $this->makeAdmin(['email' => 'owner@example.com']);
        $intruder = $this->makeAdmin(['email' => 'intruder@example.com']);
        $notification = Notification::factory()->forAdmin($owner->id)->unread()->create();

        $response = $this->actingAs($intruder, 'admin')->post(route('admin.notifications.read', $notification->id));

        $response->assertForbidden();
        $this->assertNull($notification->fresh()->read_at);
    }

    public function test_admin_can_mark_all_notifications_as_read(): void
    {
        $admin = $this->makeAdmin();
        Notification::factory()->forAdmin($admin->id)->unread()->count(2)->create();

        $this->actingAs($admin, 'admin')->post(route('admin.notifications.readAll'));

        $this->assertEquals(0, $admin->fresh()->unreadNotificationsCount());
    }

    public function test_unread_filter_only_shows_unread_notifications(): void
    {
        $admin = $this->makeAdmin();
        Notification::factory()->forAdmin($admin->id)->unread()->create(['title' => 'Critical shortage']);
        Notification::factory()->forAdmin($admin->id)->read()->create(['title' => 'Old alert']);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.notifications.index', ['status' => 'unread']));

        $response->assertOk();
        $response->assertSee('Critical shortage');
        $response->assertDontSee('Old alert');
    }
}
