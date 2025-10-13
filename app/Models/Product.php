<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi mass-assignment
     */
    protected $fillable = [
        'name',
        'price',
        'foto',
        'category_id',
        'discount',
        'discount_active',
        'discount_user_ids',
    ];

    /**
     * Konversi otomatis kolom JSON menjadi array
     */
    protected $casts = [
        'discount_active' => 'boolean',
        'discount_user_ids' => 'array',
    ];

    /**
     * Relasi many-to-one ke Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Accessor: harga akhir (setelah diskon jika aktif)
     */
    public function getFinalPriceAttribute()
    {
        return ($this->discount_active && $this->discount)
            ? $this->price - $this->discount
            : $this->price;
    }

    /**
     * Cek apakah user tertentu berhak dapat diskon
     */
    public function userHasDiscount($userId)
    {
        if (! $this->discount_active || empty($this->discount_user_ids)) {
            return false;
        }

        return in_array($userId, $this->discount_user_ids);
    }
}
