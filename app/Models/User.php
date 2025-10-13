<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Field yang bisa diisi mass-assignment
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'detail_alamat', // Tambahan: detail alamat
    ];

    // Field yang disembunyikan saat array/json
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casting tipe data
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // App\Models\User.php

public function orders()
{
    return $this->hasMany(Order::class);
}

}
