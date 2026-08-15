<?php

namespace Tests\Feature\Donor;

use App\Models\Donor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FindDonorsTest extends TestCase
{
    use RefreshDatabase;

    public function test_find_donors_page_returns_only_eligible_matching_donors(): void
    {
        Donor::factory()->create(['blood_group' => 'O+', 'district' => 'Colombo', 'is_eligible' => true]);
        Donor::factory()->create(['blood_group' => 'A+', 'district' => 'Colombo', 'is_eligible' => true]);
        Donor::factory()->create(['blood_group' => 'O+', 'district' => 'Colombo', 'is_eligible' => false]);

        $response = $this->get(route('find-donors', ['blood_group' => 'O+', 'district' => 'Colombo']));

        $response->assertOk();
        $response->assertSee('1 eligible donor found');
    }

    public function test_find_donors_page_loads_with_no_filters(): void
    {
        $response = $this->get(route('find-donors'));

        $response->assertOk();
    }
}
