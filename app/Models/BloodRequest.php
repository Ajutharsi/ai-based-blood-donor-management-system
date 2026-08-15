<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id', 'blood_group', 'units_needed',
        'urgency', 'ward', 'required_by',
        'notes', 'status',
    ];

    protected function casts(): array
    {
        return ['required_by' => 'date'];
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function responses()
    {
        return $this->hasMany(DonorResponse::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}