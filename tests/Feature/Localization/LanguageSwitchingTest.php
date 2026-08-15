<?php

namespace Tests\Feature\Localization;

use App\Models\Admin;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguageSwitchingTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(array $overrides = []): Admin
    {
        return Admin::create(array_merge([
            'name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password'),
        ], $overrides));
    }

    public function test_default_locale_is_english_for_a_fresh_guest(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $this->assertSame('en', app()->getLocale());
    }

    public function test_guest_can_switch_language_and_it_applies_via_session(): void
    {
        $this->withSession([]);
        $response = $this->post(route('language.switch', 'si'));

        $response->assertRedirect();
        $this->assertSame('si', session('locale'));

        // The next request in the same session should render in Sinhala.
        $follow = $this->get(route('donor.login'));
        $follow->assertOk();
        $this->assertSame('si', app()->getLocale());
    }

    public function test_switching_to_an_unsupported_locale_is_rejected(): void
    {
        $response = $this->post(route('language.switch', 'fr'));

        $response->assertNotFound();
        $this->assertNull(session('locale'));
    }

    public function test_donor_switching_language_persists_to_their_own_record(): void
    {
        $donor = Donor::factory()->create(['locale' => 'en']);

        $response = $this->actingAs($donor, 'donor')->post(route('language.switch', 'ta'));

        $response->assertRedirect();
        $this->assertSame('ta', $donor->fresh()->locale);
    }

    public function test_hospital_switching_language_persists_to_their_own_record(): void
    {
        $hospital = Hospital::factory()->create(['locale' => 'en']);

        $this->actingAs($hospital, 'hospital')->post(route('language.switch', 'si'));

        $this->assertSame('si', $hospital->fresh()->locale);
    }

    public function test_admin_switching_language_persists_to_their_own_record(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin, 'admin')->post(route('language.switch', 'ta'));

        $this->assertSame('ta', $admin->fresh()->locale);
    }

    public function test_logged_in_donor_dashboard_renders_in_their_stored_locale(): void
    {
        $donor = Donor::factory()->create(['locale' => 'si']);

        $response = $this->actingAs($donor, 'donor')->get(route('donor.dashboard'));

        $response->assertOk();
        $this->assertSame('si', app()->getLocale());
    }

    public function test_stored_user_locale_takes_priority_over_stale_session_locale(): void
    {
        $donor = Donor::factory()->create(['locale' => 'ta']);

        $response = $this->actingAs($donor, 'donor')
            ->withSession(['locale' => 'si'])
            ->get(route('donor.dashboard'));

        $response->assertOk();
        $this->assertSame('ta', app()->getLocale());
    }

    public function test_invalid_session_locale_falls_back_to_default(): void
    {
        $response = $this->withSession(['locale' => 'xx'])->get(route('home'));

        $response->assertOk();
        $this->assertSame('en', app()->getLocale());
    }

    public function test_login_page_renders_translated_text_when_locale_is_sinhala(): void
    {
        $response = $this->withSession(['locale' => 'si'])->get(route('donor.login'));

        $response->assertOk();
        $response->assertSee('පුරන්න'); // "Sign In"
    }

    public function test_login_page_renders_translated_text_when_locale_is_tamil(): void
    {
        $response = $this->withSession(['locale' => 'ta'])->get(route('donor.login'));

        $response->assertOk();
        $response->assertSee('உள்நுழை'); // "Sign In"
    }

    public function test_login_page_renders_english_by_default(): void
    {
        $response = $this->get(route('donor.login'));

        $response->assertOk();
        $response->assertSee('Sign In');
    }

    public function test_language_selector_is_present_on_login_page(): void
    {
        $response = $this->get(route('donor.login'));

        $response->assertOk();
        $response->assertSee('lang-sel-wrap', false);
    }

    public function test_validation_errors_render_in_the_active_locale(): void
    {
        $response = $this->withSession(['locale' => 'si'])
            ->post(route('hospital.register'), []);

        $response->assertSessionHasErrors();
        $errors = session('errors')->getBag('default');
        $this->assertStringContainsString('අවශ්‍යයි', $errors->first('name'));
    }
}
