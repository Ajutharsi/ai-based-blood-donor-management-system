<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(array $overrides = []): Admin
    {
        return Admin::create(array_merge([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => Hash::make('password123'),
        ], $overrides));
    }

    public function test_guest_cannot_access_admin_profile_edit_page(): void
    {
        $this->get(route('admin.profile.edit'))->assertRedirect();
    }

    public function test_admin_can_view_profile_edit_page(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.profile.edit'));

        $response->assertOk();
        $response->assertSee($admin->email);
    }

    public function test_admin_can_update_name_and_email(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin, 'admin')->put(route('admin.profile.update'), [
            'name'  => 'Updated Admin Name',
            'email' => 'updated-admin@example.com',
        ]);

        $response->assertRedirect(route('admin.profile.edit'));
        $this->assertDatabaseHas('admins', [
            'id'    => $admin->id,
            'name'  => 'Updated Admin Name',
            'email' => 'updated-admin@example.com',
        ]);
    }

    public function test_profile_update_rejects_email_taken_by_another_admin(): void
    {
        $this->makeAdmin(['email' => 'taken@example.com']);
        $admin = $this->makeAdmin(['email' => 'me@example.com']);

        $response = $this->actingAs($admin, 'admin')->put(route('admin.profile.update'), [
            'name'  => 'Admin',
            'email' => 'taken@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_admin_can_change_password(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin, 'admin')->put(route('admin.profile.update'), [
            'name'                  => $admin->name,
            'email'                 => $admin->email,
            'current_password'      => 'password123',
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $this->assertTrue(Hash::check('newpassword123', $admin->fresh()->password));
    }
}
