<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BloodInventory extends Model
{
    use HasFactory;

    protected $table = 'blood_inventory';

    protected $fillable = [
        'hospital_id', 'blood_group', 'available_units', 'minimum_threshold', 'last_updated',
    ];

    protected function casts(): array
    {
        return [
            'last_updated' => 'datetime',
        ];
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(BloodInventoryLog::class)->latest();
    }

    // Critical below half the minimum threshold (or empty); low stock
    // anywhere under the threshold but above that; sufficient at or above
    // it. Mirrors the tiered severity pattern already used for the AI
    // shortage fallback in Admin\DashboardController. Static so callers can
    // compare a before/after status without needing two persisted models.
    public static function computeStatus(int $availableUnits, int $minimumThreshold): string
    {
        if ($availableUnits <= 0 || $availableUnits <= $minimumThreshold / 2) {
            return 'critical';
        }

        if ($availableUnits < $minimumThreshold) {
            return 'low_stock';
        }

        return 'sufficient';
    }

    public static function statusLabelFor(string $status): string
    {
        return match ($status) {
            'critical' => 'Critical',
            'low_stock' => 'Low Stock',
            default => 'Sufficient',
        };
    }

    public function status(): string
    {
        return self::computeStatus($this->available_units, $this->minimum_threshold);
    }

    public function statusLabel(): string
    {
        return self::statusLabelFor($this->status());
    }
}
