<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback'; // Nama tabel di database

    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'pesan',
        'rating',
    ];

    // Relasi ke user (jika ada user_id)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
