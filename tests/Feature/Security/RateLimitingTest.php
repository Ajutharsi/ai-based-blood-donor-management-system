<?php

namespace Tests\Feature\Security;

use App\Models\Admin;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RateLimitingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Avoids each of the 16 chat-throttle-test requests making a real
        // (slow/failing) network call to the Python AI service.
        Http::fake(['*/chatbot' => Http::response(['reply' => 'hi', 'status' => 'success'])]);
    }

    public function test_donor_login_is_throttled_after_five_attempts(): void
    {
        $donor = Donor::factory()->create(['email' => 'donor@example.com']);

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('donor.login'), ['email' => $donor->email, 'password' => 'wrong']);
        }

        $response = $this->post(route('donor.login'), ['email' => $donor->email, 'password' => 'wrong']);

        $response->assertStatus(429);
    }

    public function test_hospital_login_is_throttled_after_five_attempts(): void
    {
        $hospital = Hospital::factory()->create(['email' => 'hospital@example.com']);

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('hospital.login'), ['email' => $hospital->email, 'password' => 'wrong']);
        }

        $response = $this->post(route('hospital.login'), ['email' => $hospital->email, 'password' => 'wrong']);

        $response->assertStatus(429);
    }

    public function test_admin_login_is_throttled_after_five_attempts(): void
    {
        $admin = Admin::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password')]);

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('admin.login'), ['email' => $admin->email, 'password' => 'wrong']);
        }

        $response = $this->post(route('admin.login'), ['email' => $admin->email, 'password' => 'wrong']);

        $response->assertStatus(429);
    }

    public function test_login_throttle_is_keyed_per_email_not_shared_across_all_attackers(): void
    {
        Donor::factory()->create(['email' => 'victim@example.com']);
        Donor::factory()->create(['email' => 'other@example.com']);

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('donor.login'), ['email' => 'victim@example.com', 'password' => 'wrong']);
        }

        // A legitimate attempt against a different account from the same IP
        // must not be blocked by another account's exhausted throttle.
        $response = $this->post(route('donor.login'), ['email' => 'other@example.com', 'password' => 'wrong']);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_successful_login_is_not_blocked_before_the_limit_is_reached(): void
    {
        $donor = Donor::factory()->create(['email' => 'donor2@example.com']);

        $response = $this->post(route('donor.login'), ['email' => $donor->email, 'password' => 'password']);

        $response->assertRedirect(route('donor.dashboard'));
    }

    public function test_donor_registration_is_throttled(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->post(route('donor.register'), ['email' => "spam{$i}@example.com"]);
        }

        $response = $this->post(route('donor.register'), ['email' => 'spam-overflow@example.com']);

        $response->assertStatus(429);
    }

    public function test_chat_endpoint_is_throttled(): void
    {
        for ($i = 0; $i < 15; $i++) {
            $this->postJson(route('chat'), ['message' => 'hello']);
        }

        $response = $this->postJson(route('chat'), ['message' => 'hello']);

        $response->assertStatus(429);
    }
}
