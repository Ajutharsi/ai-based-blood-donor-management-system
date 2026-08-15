<?php

namespace Tests\Feature\Security;

use App\Models\Admin;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_donor_cannot_change_password_without_current_password(): void
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->put(route('donor.profile.update'), [
            'first_name' => $donor->first_name,
            'last_name'  => $donor->last_name,
            'email'      => $donor->email,
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('password', $donor->fresh()->password));
    }

    public function test_donor_cannot_change_password_with_wrong_current_password(): void
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->put(route('donor.profile.update'), [
            'first_name'       => $donor->first_name,
            'last_name'        => $donor->last_name,
            'email'            => $donor->email,
            'current_password' => 'totally-wrong',
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('password', $donor->fresh()->password));
    }

    public function test_donor_can_change_password_with_correct_current_password(): void
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->put(route('donor.profile.update'), [
            'first_name'       => $donor->first_name,
            'last_name'        => $donor->last_name,
            'email'            => $donor->email,
            'current_password' => 'password',
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('newpassword123', $donor->fresh()->password));
    }

    public function test_donor_can_update_other_fields_without_current_password(): void
    {
        // Routine profile edits that don't touch the password shouldn't
        // require re-authentication -- only an actual password change does.
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->put(route('donor.profile.update'), [
            'first_name' => 'Updated',
            'last_name'  => $donor->last_name,
            'email'      => $donor->email,
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_hospital_cannot_change_password_without_current_password(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->put(route('hospital.profile.update'), [
            'name'  => $hospital->name,
            'email' => $hospital->email,
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('password', $hospital->fresh()->password));
    }

    public function test_admin_cannot_change_password_without_current_password(): void
    {
        $admin = Admin::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('password123')]);

        $response = $this->actingAs($admin, 'admin')->put(route('admin.profile.update'), [
            'name'  => $admin->name,
            'email' => $admin->email,
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('password123', $admin->fresh()->password));
    }
}
