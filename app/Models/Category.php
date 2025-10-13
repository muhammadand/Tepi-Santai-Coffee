<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Jika nama tabel beda dari konvensi Laravel (bentuk jamak dari nama model), bisa ditentukan di sini
    // protected $table = 'categories';

    // Kolom yang boleh diisi secara mass assignment
    protected $fillable = [
        'name',
    ];

    // Jika tidak menggunakan timestamps, bisa dinonaktifkan:
    // public $timestamps = false;

    // Contoh relasi (jika ada, misal kategori punya produk)
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
