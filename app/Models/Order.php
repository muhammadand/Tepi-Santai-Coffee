<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'detail_alamat',
        'midtrans_order_id',
        'payment_status',
        'status_order',       // kolom baru
        'payment_method'      // kolom baru: tunai atau non-tunai
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
