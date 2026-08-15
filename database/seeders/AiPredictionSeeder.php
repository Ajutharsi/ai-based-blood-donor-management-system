<?php

namespace Database\Seeders;

use App\Models\AiPrediction;
use App\Models\BloodRequest;
use App\Models\Donor;
use Illuminate\Database\Seeder;

class AiPredictionSeeder extends Seeder
{
    private const BLOOD_GROUPS = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];

    public function run(): void
    {
        $donorLogCount = 0;

        // Eligibility + response + anomaly predictions for most donors,
        // built directly from each donor's own already-seeded AI fields so
        // the audit log is internally consistent with what's shown on the
        // donor/admin screens (rather than independently-random numbers).
        $donors = Donor::inRandomOrder()->take((int) round(Donor::count() * 0.8))->get();

        foreach ($donors as $donor) {
            AiPrediction::factory()->eligibilityFor($donor)->create();
            AiPrediction::factory()->responseFor($donor)->create();
            $donorLogCount += 2;

            if (fake()->boolean(60)) {
                AiPrediction::factory()->anomalyFor($donor)->create();
                $donorLogCount++;
            }
        }

        // Shortage + forecast snapshots per blood group, using the real
        // donor/request counts already in the DB, spread over the last two
        // weeks so the AI Predictions audit log shows a history rather than
        // everything stamped with an identical timestamp.
        $shortageCount = 0;
        $forecastCount = 0;

        foreach (self::BLOOD_GROUPS as $bloodGroup) {
            $eligibleCount = Donor::where('blood_group', $bloodGroup)->where('is_eligible', true)->count();
            $totalDonors = Donor::where('blood_group', $bloodGroup)->count();
            $requestsLastMonth = BloodRequest::where('blood_group', $bloodGroup)
                ->where('created_at', '>=', now()->subMonth())
                ->count();

            $weeklyCounts = [];
            for ($w = 8; $w >= 1; $w--) {
                $weeklyCounts[] = BloodRequest::where('blood_group', $bloodGroup)
                    ->whereBetween('created_at', [
                        now()->subWeeks($w)->startOfWeek(),
                        now()->subWeeks($w)->endOfWeek(),
                    ])->count();
            }

            for ($snapshot = 0; $snapshot < 5; $snapshot++) {
                $timestamp = now()->subDays(fake()->numberBetween(0, 13))->subHours(fake()->numberBetween(0, 23));

                $shortagePrediction = AiPrediction::factory()
                    ->shortage($bloodGroup, $eligibleCount, $totalDonors, $requestsLastMonth)
                    ->create();
                $shortagePrediction->forceFill(['created_at' => $timestamp, 'updated_at' => $timestamp])->save();
                $shortageCount++;

                $forecastPrediction = AiPrediction::factory()
                    ->forecast($bloodGroup, $weeklyCounts, $eligibleCount)
                    ->create();
                $forecastPrediction->forceFill(['created_at' => $timestamp, 'updated_at' => $timestamp])->save();
                $forecastCount++;
            }
        }

        $total = AiPrediction::count();
        $this->command?->info("  AI predictions seeded: {$donorLogCount} donor-linked (eligibility/response/anomaly) + {$shortageCount} shortage + {$forecastCount} forecast = {$total} total");
    }
}
