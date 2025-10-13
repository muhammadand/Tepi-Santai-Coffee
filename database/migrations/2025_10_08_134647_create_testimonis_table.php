<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonis', function (Blueprint $table) {
            $table->id();

            // Relasi ke user yang memberi testimoni
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Relasi ke produk yang diberi testimoni
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

            // (Opsional) relasi ke order_item agar tahu testimoni ini dari pembelian mana
            $table->foreignId('order_item_id')->nullable()->constrained('order_items')->onDelete('cascade');

            // Isi testimoni
            $table->tinyInteger('rating')->comment('1-5 bintang');
            $table->text('content')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonis');
    }
};
