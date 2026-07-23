<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Donor;
use App\Services\AiEligibilityService;
use Illuminate\Support\Facades\Log;

class ReCheckEligibility extends Command
{
    protected $signature   = 'ai:recheck-eligibility';
    protected $description = 'Re-check all donor eligibility using AI model';

    public function handle()
    {
        $this->info('🤖 Starting AI eligibility re-check...');

        $ai      = new AiEligibilityService();
        $donors  = Donor::all();
        $total   = $donors->count();
        $updated = 0;
        $changed = 0;

        if ($total === 0) {
            $this->warn('No donors found.');
            return;
        }

        $this->info("Found {$total} donors to re-check.");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($donors as $donor) {
            try {
                // Call Python AI
                $result = $ai->predict([
                    'age'             => $donor->age             ?? 0,
                    'weight_kg'       => $donor->weight_kg       ?? 0,
                    'hemoglobin'      => $donor->hemoglobin      ?? 0,
                    'total_donations' => $donor->total_donations ?? 0,
                    'blood_group'     => $donor->blood_group     ?? 'O+',
                    'gender'          => $donor->gender          ?? 'Male',
                ]);

                $newEligible    = $result['eligible'];
                $newConfidence  = $result['confidence'];
                $oldEligible    = $donor->is_eligible;

                // Update donor
                $donor->update([
                    'is_eligible'   => $newEligible,
                    'ai_confidence' => $newConfidence,
                ]);

                // Track changes
                if ($oldEligible !== $newEligible) {
                    $changed++;
                    Log::info("AI Re-check: Donor #{$donor->id} ({$donor->full_name}) " .
                        "eligibility changed: " .
                        ($oldEligible ? 'Eligible' : 'Not Eligible') .
                        " → " .
                        ($newEligible ? 'Eligible' : 'Not Eligible'));
                }

                $updated++;

            } catch (\Exception $e) {
                Log::warning("AI Re-check failed for donor #{$donor->id}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Re-check complete!");
        $this->info("   Total donors: {$total}");
        $this->info("   Updated:      {$updated}");
        $this->info("   Changed:      {$changed}");

        // Log summary
        Log::info("AI Re-check Summary: {$total} donors checked, {$updated} updated, {$changed} eligibility changes.");
    }
}