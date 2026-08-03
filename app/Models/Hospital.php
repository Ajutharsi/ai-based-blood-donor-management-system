<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Hospital extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'hospital';

    protected $fillable = [
        'name', 'email', 'password',
        'registration_id', 'phone',
        'city', 'district', 'address',
        'is_verified',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password'    => 'hashed',
            'is_verified' => 'boolean',
        ];
    }

    public function bloodRequests()
    {
        return $this->hasMany(BloodRequest::class);
    }
}