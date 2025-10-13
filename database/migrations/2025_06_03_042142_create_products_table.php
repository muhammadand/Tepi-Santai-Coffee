<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->decimal('price', 15, 2); // harga dengan 2 desimal
            $table->decimal('discount', 15, 2)->nullable(); // kolom diskon
            $table->boolean('discount_active')->nullable()->default(false); // aktifkan diskon atau tidak
            $table->json('discount_user_ids')->nullable()->comment('Daftar user_id yang dapat diskon');
            $table->string('foto')->nullable();

            // foreign key category_id
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')
                  ->references('id')->on('categories')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
