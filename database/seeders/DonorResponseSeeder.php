<?php

namespace Database\Seeders;

use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\DonorResponse;
use App\Support\BloodCompatibility;
use Illuminate\Database\Seeder;

class DonorResponseSeeder extends Seeder
{
    public function run(): void
    {
        // Only pending/fulfilled requests from the most recent activity get
        // responses -- mirrors reality, where donors only ever respond to
        // requests that were actually shown to them (BloodRequestController
        // only lists compatible+eligible-donor-visible, non-cancelled
        // requests). Cap at 60 requests to keep the seed fast.
        $bloodRequests = BloodRequest::whereIn('status', ['pending', 'fulfilled'])
            ->latest()
            ->take(60)
            ->get();

        $created = 0;

        foreach ($bloodRequests as $bloodRequest) {
            $compatibleGroups = BloodCompatibility::compatibleDonorGroups($bloodRequest->blood_group);

            $candidates = Donor::whereIn('blood_group', $compatibleGroups)
                ->where('is_eligible', true)
                ->inRandomOrder()
                ->take(fake()->numberBetween(2, 6))
                ->get();

            foreach ($candidates as $donor) {
                $response = DonorResponse::firstOrNew([
                    'donor_id'         => $donor->id,
                    'blood_request_id' => $bloodRequest->id,
                ]);

                if ($response->exists) {
                    continue; // unique(donor_id, blood_request_id) already satisfied
                }

                $response->status = fake()->randomElement(['available', 'available', 'available', 'not_available']);
                $response->responded_at = fake()->dateTimeBetween($bloodRequest->created_at, 'now');
                $response->save();

                $created++;
            }
        }

        $this->command?->info("  Donor responses seeded: {$created} (only compatible + eligible donors, unique per request)");
    }
}
