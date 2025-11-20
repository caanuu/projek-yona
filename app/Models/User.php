<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username', // Tambahan dari Doc
        'email',
        'password',
        'role', // admin, gudang, kasir
    ];

    protected $hidden = ['password', 'remember_token'];

    // Helper Role
    public function isAdmin() { return $this->role === 'admin'; }
    public function isGudang() { return $this->role === 'gudang'; }
    public function isKasir() { return $this->role === 'kasir'; }
}
