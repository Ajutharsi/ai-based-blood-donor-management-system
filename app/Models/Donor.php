<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Donor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'donor';

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
        'phone', 'date_of_birth', 'age', 'gender', 'nic',
        'blood_group', 'weight_kg', 'hemoglobin',
        'total_donations', 'last_donation_date',
        'city', 'district', 'donation_center',
        'profile_image', 'medical_condition',
        'medical_notes', 'is_eligible', 'ai_confidence','response_probability',
        'response_level', 'is_anomaly',  'anomaly_score','last_ai_check',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'last_donation_date' => 'date',
            'is_eligible' => 'boolean',
        ];
    }

    // Full name helper
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class)->latest('donation_date');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(DonorResponse::class);
    }
}