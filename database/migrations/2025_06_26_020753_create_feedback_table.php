<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // jika ingin dikaitkan dengan user login
            $table->string('nama');
            $table->string('email')->nullable();
            $table->text('pesan'); // isi feedback dari pelanggan
            $table->enum('rating', ['1', '2', '3', '4', '5'])->nullable(); // rating opsional
            $table->timestamps();

            // relasi opsional jika ada user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
