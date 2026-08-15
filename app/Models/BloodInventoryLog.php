<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloodInventoryLog extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'blood_inventory_id', 'hospital_id', 'blood_group', 'action', 'units_before', 'units_after', 'note',
    ];

    public function bloodInventory(): BelongsTo
    {
        return $this->belongsTo(BloodInventory::class);
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }
}
