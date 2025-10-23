<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'kabupaten',
        'kecamatan',      // baru
        'desa',           // baru
        'detail_alamat',
        'ongkir',         // baru
        'total',          // baru
        'midtrans_order_id',
        'payment_status',
        'status_order',
        'payment_method'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
