<?php

namespace Database\Seeders;

use App\Models\Donor;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    private const CENTERS = ['NBTS Colombo', 'NBTS Kandy', 'NBTS Galle', 'NBTS Jaffna', 'NBTS Kurunegala', 'NBTS Ratnapura'];

    public function run(): void
    {
        // Give ~55% of donors a real donation history (0-4 donations each),
        // spread over the last 2 years, then reconcile total_donations and
        // last_donation_date exactly the way Admin\DonorController::storeDonation
        // does for a real single donation -- so seeded donors are never left
        // in the "total_donations counted but no rows exist" inconsistent
        // state the task explicitly calls out.
        $donors = Donor::inRandomOrder()->take((int) round(Donor::count() * 0.55))->get();
        $donationCount = 0;

        foreach ($donors as $donor) {
            $numDonations = fake()->numberBetween(1, 4);
            $dates = [];

            for ($i = 0; $i < $numDonations; $i++) {
                $date = fake()->dateTimeBetween('-2 years', '-1 week')->format('Y-m-d');
                $dates[] = $date;

                $donor->donations()->create([
                    'donation_date'   => $date,
                    'blood_group'     => $donor->blood_group,
                    'donation_center' => fake()->randomElement(self::CENTERS),
                    'units'           => fake()->numberBetween(1, 2),
                    'notes'           => null,
                ]);

                $donationCount++;
            }

            $donor->update([
                'total_donations'    => $numDonations,
                'last_donation_date' => collect($dates)->max(),
            ]);
        }

        $this->command?->info("  Donations seeded: {$donationCount} across {$donors->count()} donors (total_donations/last_donation_date reconciled)");
    }
}
