<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->string('detail_alamat');
            $table->string('midtrans_order_id')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('status_order')->default('dipesan');
            $table->string('payment_method')->default('tunai'); // metode pembayaran: tunai atau non-tunai
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
