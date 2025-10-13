<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();               // id auto increment
            $table->string('name');     // nama kategori
            $table->timestamps();       // created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
