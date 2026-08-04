<?php

namespace App\Services;

use App\Models\BloodInventory;
use App\Models\BloodInventoryLog;
use App\Models\Notification;

/**
 * Centralises every blood_inventory mutation (manual add/remove, threshold
 * changes, automatic deduction on fulfillment) so unit adjustments, the
 * audit log, and status-transition notifications always happen together --
 * no controller ever updates available_units directly.
 */
class BloodInventoryService
{
    // Returns the inventory row for a hospital+blood group, creating it
    // (0 units, default threshold) the first time it's touched -- this is
    // what keeps "one row per hospital per blood group" true without a
    // separate "create a blood group" step in the UI.
    public function findOrCreate(int $hospitalId, string $bloodGroup): BloodInventory
    {
        return BloodInventory::firstOrCreate(
            ['hospital_id' => $hospitalId, 'blood_group' => $bloodGroup],
            ['available_units' => 0, 'minimum_threshold' => 10, 'last_updated' => now()]
        );
    }

    public function addUnits(BloodInventory $inventory, int $units, ?string $note = null): BloodInventory
    {
        return $this->adjust($inventory, $units, 'add', $note);
    }

    // Removes up to $units, clamped so available_units never goes below
    // zero. Returns the updated inventory plus whether the requested amount
    // was fully honoured, so callers can flash a validation message when it
    // wasn't (e.g. "only 3 of the 5 requested units were available").
    public function removeUnits(BloodInventory $inventory, int $units, ?string $note = null): array
    {
        $actuallyRemoved = min($units, $inventory->available_units);
        $inventory = $this->adjust($inventory, -$actuallyRemoved, 'remove', $note);

        return ['inventory' => $inventory, 'fully_honoured' => $actuallyRemoved === $units, 'removed' => $actuallyRemoved];
    }

    public function updateThreshold(BloodInventory $inventory, int $minimumThreshold): BloodInventory
    {
        $oldStatus = $inventory->status();

        $inventory->update(['minimum_threshold' => $minimumThreshold, 'last_updated' => now()]);

        BloodInventoryLog::create([
            'blood_inventory_id' => $inventory->id,
            'hospital_id'        => $inventory->hospital_id,
            'blood_group'        => $inventory->blood_group,
            'action'             => 'threshold_update',
            'units_before'       => $inventory->available_units,
            'units_after'        => $inventory->available_units,
            'note'               => "Minimum threshold set to {$minimumThreshold}",
        ]);

        $this->notifyOnTransition($inventory, $oldStatus, $inventory->status());

        return $inventory->fresh();
    }

    // Used by Hospital\BloodRequestController::fulfill() -- deducts stock
    // for a fulfilled request's blood group, never going below zero.
    // Returns whether recorded stock fully covered the request, so the
    // caller can show a validation warning without blocking the
    // fulfillment itself (fulfilling a request is the primary action;
    // inventory bookkeeping is secondary).
    public function deductForFulfillment(int $hospitalId, string $bloodGroup, int $unitsNeeded): array
    {
        $inventory = BloodInventory::where('hospital_id', $hospitalId)
            ->where('blood_group', $bloodGroup)
            ->first();

        if (!$inventory) {
            // No inventory tracked for this blood group yet -- nothing to deduct.
            return ['tracked' => false, 'fully_covered' => true];
        }

        $actuallyRemoved = min($unitsNeeded, $inventory->available_units);
        $this->adjust($inventory, -$actuallyRemoved, 'fulfillment', "Deducted for fulfilled blood request ({$unitsNeeded} unit(s) requested)");

        return ['tracked' => true, 'fully_covered' => $actuallyRemoved === $unitsNeeded, 'removed' => $actuallyRemoved];
    }

    private function adjust(BloodInventory $inventory, int $delta, string $action, ?string $note): BloodInventory
    {
        $before = $inventory->available_units;
        $after = max(0, $before + $delta);
        $oldStatus = BloodInventory::computeStatus($before, $inventory->minimum_threshold);

        $inventory->update(['available_units' => $after, 'last_updated' => now()]);

        BloodInventoryLog::create([
            'blood_inventory_id' => $inventory->id,
            'hospital_id'        => $inventory->hospital_id,
            'blood_group'        => $inventory->blood_group,
            'action'             => $action,
            'units_before'       => $before,
            'units_after'        => $after,
            'note'               => $note,
        ]);

        $this->notifyOnTransition($inventory, $oldStatus, $inventory->status());

        return $inventory->fresh();
    }

    // Only fires when the status actually changes tier, so repeated updates
    // while already "low" or already "sufficient" don't spam notifications.
    private function notifyOnTransition(BloodInventory $inventory, string $oldStatus, string $newStatus): void
    {
        if ($oldStatus === $newStatus) {
            return;
        }

        $hospital = $inventory->hospital;
        $group = $inventory->blood_group;
        $units = $inventory->available_units;

        if ($newStatus === 'critical') {
            Notification::notify(
                'hospital', $inventory->hospital_id,
                'Critical blood stock',
                "{$group} stock is critical: only {$units} unit(s) left (minimum {$inventory->minimum_threshold}).",
                'inventory_critical', $inventory->id
            );
            Notification::notifyAllAdmins(
                'Critical blood stock',
                "{$hospital?->name} has critical {$group} stock: only {$units} unit(s) left.",
                'inventory_critical', $inventory->id
            );
        } elseif ($newStatus === 'low_stock') {
            Notification::notify(
                'hospital', $inventory->hospital_id,
                'Low blood stock',
                "{$group} stock is running low: {$units} unit(s) left (minimum {$inventory->minimum_threshold}).",
                'inventory_low', $inventory->id
            );
        } elseif ($newStatus === 'sufficient' && in_array($oldStatus, ['low_stock', 'critical'])) {
            Notification::notify(
                'hospital', $inventory->hospital_id,
                'Blood stock replenished',
                "{$group} stock has been replenished and is back to a sufficient level ({$units} unit(s)).",
                'inventory_replenished', $inventory->id
            );
        }
    }
}
