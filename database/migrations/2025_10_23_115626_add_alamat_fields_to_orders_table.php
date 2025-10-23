<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('kabupaten')->nullable()->after('nama');
            $table->string('kecamatan')->nullable()->after('kabupaten');
            $table->string('desa')->nullable()->after('kecamatan');
            $table->text('detail_alamat')->nullable()->change(); // pastikan boleh kosong
            $table->integer('ongkir')->default(0)->after('detail_alamat'); // tambahkan ongkir
            $table->integer('total')->default(0)->after('ongkir'); // tambahkan total
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['kabupaten', 'kecamatan', 'desa', 'ongkir', 'total']);
        });
    }
};
