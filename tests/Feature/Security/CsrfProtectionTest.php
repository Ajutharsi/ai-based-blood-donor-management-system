<?php

namespace Tests\Feature\Security;

use App\Models\Donor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CsrfProtectionTest extends TestCase
{
    use RefreshDatabase;

    // Laravel's VerifyCsrfToken middleware auto-bypasses CSRF checks whenever
    // app()->runningUnitTests() is true (the default in every other test in
    // this suite, which is why none of them attach an explicit token).
    // Flipping the bound 'env' away from "testing" for just this test makes
    // the middleware enforce CSRF for real, so this test can assert what
    // actually happens to an unauthenticated POST with no token.
    protected function withRealCsrfEnforcement(): void
    {
        $this->app['env'] = 'production';
    }

    public function test_post_without_csrf_token_is_rejected(): void
    {
        $this->withRealCsrfEnforcement();

        $donor = Donor::factory()->create();

        $response = $this->post(route('donor.login'), [
            'email' => $donor->email,
            'password' => 'password',
        ]);

        $response->assertStatus(419);
        $this->assertGuest('donor');
    }

    public function test_post_with_valid_csrf_token_succeeds(): void
    {
        $this->withRealCsrfEnforcement();

        $donor = Donor::factory()->create();

        // Warm a session + token the way a real browser would (GET the form
        // first), then submit using the test client's automatic CSRF
        // token injection for that session.
        $this->get(route('donor.login'));

        $response = $this->withSession(['_token' => 'test-token'])
            ->post(route('donor.login'), [
                '_token'   => 'test-token',
                'email'    => $donor->email,
                'password' => 'password',
            ]);

        $response->assertRedirect(route('donor.dashboard'));
    }
}
