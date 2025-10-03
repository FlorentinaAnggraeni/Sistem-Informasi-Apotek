<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'alamat',
        'no_hp',
        'role',
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

    // Helper method untuk check role
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isPelanggan()
    {
        return $this->role === 'pelanggan';
    }

    public function isPemilik()
    {
        return $this->role === 'pemilik';
    }

    public function isApoteker()
    {
        return $this->role === 'apoteker';
    }

    public function isKaryawan()
    {
        return $this->role === 'karyawan';
    }
}