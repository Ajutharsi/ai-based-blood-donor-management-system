<?php

namespace Tests\Feature\Security;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_response_includes_security_headers(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Strict-Transport-Security');
        $response->assertHeader('Content-Security-Policy');
        $response->assertHeader('Permissions-Policy');
    }

    public function test_content_security_policy_restricts_framing(): void
    {
        $response = $this->get('/');

        $csp = $response->headers->get('Content-Security-Policy');

        $this->assertStringContainsString("frame-ancestors 'self'", $csp);
        $this->assertStringContainsString("base-uri 'self'", $csp);
        $this->assertStringContainsString("form-action 'self'", $csp);
    }

    public function test_permissions_policy_still_allows_self_geolocation(): void
    {
        // The donor/hospital location picker depends on browser geolocation
        // -- the security headers must not break that existing feature.
        $response = $this->get('/');

        $this->assertStringContainsString('geolocation=(self)', $response->headers->get('Permissions-Policy'));
    }

    public function test_security_headers_are_present_on_authenticated_pages_too(): void
    {
        $donor = \App\Models\Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->get(route('donor.dashboard'));

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }
}
