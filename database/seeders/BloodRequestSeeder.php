<?php

namespace Database\Seeders;

use App\Models\BloodRequest;
use App\Models\Hospital;
use Illuminate\Database\Seeder;

class BloodRequestSeeder extends Seeder
{
    private const BLOOD_GROUPS = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
    private const URGENCIES = ['standard', 'urgent', 'critical'];
    private const WARDS = ['ICU', 'Emergency', 'Surgical', 'Maternity', 'General Medicine', 'Oncology', 'Pediatric'];

    public function run(): void
    {
        $hospitals = Hospital::all();

        if ($hospitals->isEmpty()) {
            $this->command?->warn('  Skipping BloodRequestSeeder: no hospitals found.');
            return;
        }

        $historicalCount = 0;

        // 8 weeks of history, every blood group represented every week, so
        // the admin dashboard's demand-forecast chart (which reads real
        // weekly BloodRequest counts) has an actual trend to show instead
        // of a flat line.
        for ($weeksAgo = 8; $weeksAgo >= 1; $weeksAgo--) {
            foreach (self::BLOOD_GROUPS as $bloodGroup) {
                $requestsThisWeek = fake()->numberBetween(1, 4);

                for ($i = 0; $i < $requestsThisWeek; $i++) {
                    $hospital = $hospitals->random();
                    $createdAt = now()->subWeeks($weeksAgo)
                        ->subDays(fake()->numberBetween(0, 6))
                        ->subHours(fake()->numberBetween(0, 23));

                    // Older requests are mostly resolved; recent ones skew pending.
                    $status = $weeksAgo >= 3
                        ? fake()->randomElement(['fulfilled', 'fulfilled', 'fulfilled', 'cancelled', 'pending'])
                        : fake()->randomElement(['pending', 'pending', 'fulfilled']);

                    $bloodRequest = BloodRequest::create([
                        'hospital_id'  => $hospital->id,
                        'blood_group'  => $bloodGroup,
                        'units_needed' => fake()->numberBetween(1, 6),
                        'urgency'      => fake()->randomElement(self::URGENCIES),
                        'ward'         => fake()->randomElement(self::WARDS),
                        'required_by'  => $createdAt->copy()->addDays(fake()->numberBetween(2, 14))->format('Y-m-d'),
                        'notes'        => null,
                        'status'       => $status,
                    ]);

                    // created_at/updated_at aren't mass-assignable via the
                    // model's $fillable list -- forceFill bypasses that
                    // guard specifically to backdate demo history.
                    $bloodRequest->forceFill([
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ])->save();

                    $historicalCount++;
                }
            }
        }

        // A "live" queue of fresh, mostly-pending requests (created just
        // now) so the hospital dashboard, matched-donors, notify, and
        // fulfill flows have something to demo interactively.
        $liveCount = 28;
        for ($i = 0; $i < $liveCount; $i++) {
            BloodRequest::create([
                'hospital_id'  => $hospitals->random()->id,
                'blood_group'  => fake()->randomElement(self::BLOOD_GROUPS),
                'units_needed' => fake()->numberBetween(1, 8),
                'urgency'      => fake()->randomElement(self::URGENCIES),
                'ward'         => fake()->randomElement(self::WARDS),
                'required_by'  => now()->addDays(fake()->numberBetween(1, 14))->format('Y-m-d'),
                'notes'        => fake()->boolean(30) ? fake()->sentence() : null,
                'status'       => fake()->randomElement(['pending', 'pending', 'pending', 'fulfilled']),
            ]);
        }

        $this->command?->info("  Blood requests seeded: {$historicalCount} historical (8-week trend) + {$liveCount} live = " . BloodRequest::count() . ' total');
    }
}
