<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Helper method to check if user is admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Helper method to check if user is customer
    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    // Relationship: User has many orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}