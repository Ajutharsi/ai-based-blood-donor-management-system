<?php

namespace Tests\Feature\Security;

use App\Models\Admin;
use App\Models\Donor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TestClaudeRouteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake(['api.anthropic.com/*' => Http::response(['content' => [['text' => 'hello']]])]);
    }

    public function test_guest_cannot_access_test_claude_route(): void
    {
        $response = $this->get('/test-claude');

        $response->assertRedirect(route('admin.login'));
    }

    public function test_donor_guard_cannot_access_test_claude_route(): void
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->get('/test-claude');

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_test_claude_route_and_the_api_key_is_never_disclosed(): void
    {
        $admin = Admin::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password')]);

        $response = $this->actingAs($admin, 'admin')->get('/test-claude');

        $response->assertOk();
        $response->assertJsonMissingPath('api_key');
    }
}
