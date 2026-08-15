<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Hospital extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'hospital';

    protected $fillable = [
        'name', 'email', 'password',
        'registration_id', 'phone',
        'city', 'district', 'latitude', 'longitude', 'address',
        'is_verified', 'locale',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password'    => 'hashed',
            'is_verified' => 'boolean',
            'latitude'    => 'float',
            'longitude'   => 'float',
        ];
    }

    public function hasLocation(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    public function bloodRequests()
    {
        return $this->hasMany(BloodRequest::class);
    }

    public function bloodInventory(): HasMany
    {
        return $this->hasMany(BloodInventory::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class)->latest('appointment_date');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id')->where('user_type', 'hospital')->latest();
    }

    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->whereNull('read_at')->count();
    }
}