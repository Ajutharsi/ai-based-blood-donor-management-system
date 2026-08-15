<?php

namespace Tests\Feature\Hospital;

use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HospitalProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile_edit_page(): void
    {
        $response = $this->get(route('hospital.profile.edit'));

        $response->assertRedirect(route('hospital.login'));
    }

    public function test_hospital_can_view_edit_profile_page(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->get(route('hospital.profile.edit'));

        $response->assertOk();
        $response->assertSee($hospital->email);
    }

    public function test_hospital_can_update_profile(): void
    {
        $hospital = Hospital::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($hospital, 'hospital')->put(route('hospital.profile.update'), [
            'name'     => 'New Hospital Name',
            'email'    => $hospital->email,
            'district' => 'Kandy',
        ]);

        $response->assertRedirect(route('hospital.dashboard'));
        $this->assertDatabaseHas('hospitals', [
            'id'       => $hospital->id,
            'name'     => 'New Hospital Name',
            'district' => 'Kandy',
        ]);
    }

    public function test_profile_update_validates_required_fields(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->put(route('hospital.profile.update'), [
            'name'  => '',
            'email' => $hospital->email,
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_profile_update_rejects_email_taken_by_another_hospital(): void
    {
        Hospital::factory()->create(['email' => 'other@hospital.lk']);
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->put(route('hospital.profile.update'), [
            'name'  => $hospital->name,
            'email' => 'other@hospital.lk',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_profile_update_keeps_own_email_valid(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->put(route('hospital.profile.update'), [
            'name'  => $hospital->name,
            'email' => $hospital->email,
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_profile_update_can_change_password(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->put(route('hospital.profile.update'), [
            'name'                  => $hospital->name,
            'email'                 => $hospital->email,
            'current_password'      => 'password',
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('hospital.dashboard'));
        $this->assertTrue(Hash::check('newpassword123', $hospital->fresh()->password));
    }

    public function test_profile_update_rejects_registration_id_taken_by_another_hospital(): void
    {
        Hospital::factory()->create(['registration_id' => 'HOS-TAKEN']);
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->put(route('hospital.profile.update'), [
            'name'            => $hospital->name,
            'email'           => $hospital->email,
            'registration_id' => 'HOS-TAKEN',
        ]);

        $response->assertSessionHasErrors('registration_id');
    }
}
