<?php

namespace Tests\Feature\ActivityLog;

use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminActivityLogTest extends TestCase
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

    public function test_guest_cannot_access_activity_logs(): void
    {
        $this->get(route('admin.activity-logs.index'))->assertRedirect(route('admin.login'));
    }

    public function test_donor_guard_cannot_access_admin_activity_logs(): void
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->get(route('admin.activity-logs.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_hospital_guard_cannot_access_admin_activity_logs(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->get(route('admin.activity-logs.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_activity_logs(): void
    {
        $admin = $this->makeAdmin();
        ActivityLog::factory()->create(['description' => 'A visible entry']);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.activity-logs.index'));

        $response->assertOk();
        $response->assertSee('A visible entry');
    }

    public function test_search_filters_by_description(): void
    {
        $admin = $this->makeAdmin();
        ActivityLog::factory()->create(['description' => 'Kasun Perera logged in']);
        ActivityLog::factory()->create(['description' => 'Nimal Silva logged in']);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.activity-logs.index', ['search' => 'Kasun']));

        $response->assertOk();
        $response->assertSee('Kasun Perera logged in');
        $response->assertDontSee('Nimal Silva logged in');
    }

    public function test_search_filters_by_actor_name(): void
    {
        $admin = $this->makeAdmin();
        ActivityLog::factory()->forActor('donor', 1, 'Findable Donor')->create(['description' => 'Entry A']);
        ActivityLog::factory()->forActor('donor', 2, 'Other Donor')->create(['description' => 'Entry B']);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.activity-logs.index', ['search' => 'Findable']));

        $response->assertOk();
        $response->assertSee('Entry A');
        $response->assertDontSee('Entry B');
    }

    public function test_category_filter_narrows_results(): void
    {
        $admin = $this->makeAdmin();
        ActivityLog::factory()->category('auth', 'login')->create(['description' => 'Auth entry']);
        ActivityLog::factory()->category('donation', 'donation_recorded')->create(['description' => 'Donation entry']);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.activity-logs.index', ['category' => 'auth']));

        $response->assertOk();
        $response->assertSee('Auth entry');
        $response->assertDontSee('Donation entry');
    }

    public function test_actor_type_filter_narrows_results(): void
    {
        $admin = $this->makeAdmin();
        ActivityLog::factory()->forActor('donor', 1, 'Donor Actor')->create(['description' => 'Donor entry']);
        ActivityLog::factory()->forActor('hospital', 1, 'Hospital Actor')->create(['description' => 'Hospital entry']);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.activity-logs.index', ['actor_type' => 'hospital']));

        $response->assertOk();
        $response->assertSee('Hospital entry');
        $response->assertDontSee('Donor entry');
    }

    public function test_date_range_filter_narrows_results(): void
    {
        $admin = $this->makeAdmin();
        $old = ActivityLog::factory()->create(['description' => 'Old entry']);
        $old->forceFill(['created_at' => now()->subDays(30)])->save();
        ActivityLog::factory()->create(['description' => 'Recent entry']);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.activity-logs.index', ['date_from' => now()->subDay()->format('Y-m-d')]));

        $response->assertOk();
        $response->assertSee('Recent entry');
        $response->assertDontSee('Old entry');
    }

    public function test_pagination_limits_to_twenty_per_page(): void
    {
        $admin = $this->makeAdmin();
        ActivityLog::factory()->count(25)->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.activity-logs.index'));

        $response->assertOk();
        $response->assertViewHas('logs', function ($logs) {
            return $logs->count() === 20 && $logs->total() === 25 && $logs->hasPages();
        });
    }

    public function test_second_page_returns_remaining_entries(): void
    {
        $admin = $this->makeAdmin();
        ActivityLog::factory()->count(25)->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.activity-logs.index', ['page' => 2]));

        $response->assertOk();
        $response->assertViewHas('logs', fn ($logs) => $logs->count() === 5);
    }

    // ── REPORT EXPORT ─────────────────────────────────────

    public function test_activity_logs_report_type_is_viewable(): void
    {
        $admin = $this->makeAdmin();
        ActivityLog::factory()->create(['description' => 'Reportable entry']);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.reports.index', ['type' => 'activity_logs']));

        $response->assertOk();
        $response->assertSee('Activity Log Report');
        $response->assertSee('Reportable entry');
    }

    public function test_activity_logs_report_category_filter_narrows_results(): void
    {
        $admin = $this->makeAdmin();
        ActivityLog::factory()->category('auth', 'login')->create(['description' => 'Auth report entry']);
        ActivityLog::factory()->category('donation', 'donation_recorded')->create(['description' => 'Donation report entry']);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.reports.index', ['type' => 'activity_logs', 'status' => 'auth']));

        $response->assertOk();
        $response->assertSee('Auth report entry');
        $response->assertDontSee('Donation report entry');
    }

    public function test_activity_logs_report_exports_as_pdf(): void
    {
        $admin = $this->makeAdmin();
        ActivityLog::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.reports.export', ['type' => 'activity_logs', 'format' => 'pdf']));

        $response->assertOk();
        $this->assertStringContainsString('pdf', $response->headers->get('Content-Type'));
    }

    public function test_activity_logs_report_exports_as_excel(): void
    {
        $admin = $this->makeAdmin();
        ActivityLog::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.reports.export', ['type' => 'activity_logs', 'format' => 'excel']));

        $response->assertOk();
        $this->assertStringContainsString('spreadsheet', $response->headers->get('Content-Type'));
    }

    public function test_hospital_cannot_export_activity_logs_report(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')
            ->get(route('hospital.reports.export', ['type' => 'activity_logs', 'format' => 'pdf']));

        $response->assertForbidden();
    }
}
