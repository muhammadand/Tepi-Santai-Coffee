<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OLAPDataSeeder extends Seeder
{
    public function run(): void
    {
        // --- 1️⃣ USERS ---
        DB::table('users')->insert([
            [
                'name' => 'Admin Cafe',
                'email' => 'admin@cafe.com',
                'role' => 'admin',
                'password' => bcrypt('password'),
                'detail_alamat' => 'Jl. Raya Cafe No. 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kasir Cafe',
                'email' => 'kasir@cafe.com',
                'role' => 'kasir',
                'password' => bcrypt('password'),
                'detail_alamat' => 'Jl. Raya Cafe No. 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Buat 20 pelanggan
        for ($i = 1; $i <= 20; $i++) {
            DB::table('users')->insert([
                'name' => "Pelanggan {$i}",
                'email' => "pelanggan{$i}@mail.com",
                'role' => 'pelanggan',
                'password' => bcrypt('password'),
                'detail_alamat' => 'Alamat pelanggan ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // --- 2️⃣ CATEGORIES ---
        $categories = ['Coffee', 'Non-Coffee', 'Snack', 'Dessert', 'Special Menu'];
        foreach ($categories as $cat) {
            DB::table('categories')->insert([
                'name' => $cat,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // --- 3️⃣ PRODUCTS ---
        $products = [
            ['Espresso', 'Coffee'],
            ['Cappuccino', 'Coffee'],
            ['Latte', 'Coffee'],
            ['Matcha Latte', 'Non-Coffee'],
            ['Milk Tea', 'Non-Coffee'],
            ['French Fries', 'Snack'],
            ['Donut', 'Dessert'],
            ['Brownies', 'Dessert'],
            ['Nasi Goreng Spesial', 'Special Menu'],
            ['Mie Goreng Spesial', 'Special Menu'],
        ];

        foreach ($products as [$name, $categoryName]) {
            $categoryId = DB::table('categories')->where('name', $categoryName)->value('id');
            DB::table('products')->insert([
                'name' => $name,
                'price' => rand(15000, 35000),
                'discount' => rand(0, 5000),
                'discount_active' => (bool)rand(0, 1),
                'foto' => null,
                'category_id' => $categoryId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // --- 4️⃣ ORDERS + ORDER ITEMS (1 tahun 2024) ---
        $pelangganIds = DB::table('users')->where('role', 'pelanggan')->pluck('id')->toArray();
        $productList = DB::table('products')->get();

        // buat data tiap bulan dari Jan - Desember 2024
        for ($month = 1; $month <= 12; $month++) {
            // setiap bulan ada 40 transaksi acak
            for ($i = 1; $i <= 40; $i++) {
                $userId = $pelangganIds[array_rand($pelangganIds)];
                $tanggalOrder = Carbon::create(2024, $month, rand(1, 28))->toDateTimeString();

                $orderId = DB::table('orders')->insertGetId([
                    'user_id' => $userId,
                    'nama' => DB::table('users')->where('id', $userId)->value('name'),
                    'detail_alamat' => DB::table('users')->where('id', $userId)->value('detail_alamat'),
                    'payment_status' => 'success',
                    'status_order' => 'selesai',
                    'payment_method' => rand(0, 1) ? 'tunai' : 'non-tunai',
                    'created_at' => $tanggalOrder,
                    'updated_at' => $tanggalOrder,
                ]);

                // setiap order ada 1–4 item
                $itemCount = rand(1, 4);
                for ($j = 0; $j < $itemCount; $j++) {
                    $product = $productList->random();
                    DB::table('order_items')->insert([
                        'order_id' => $orderId,
                        'produk' => $product->name,
                        'quantity' => rand(1, 3),
                        'price' => $product->price,
                        'created_at' => $tanggalOrder,
                        'updated_at' => $tanggalOrder,
                    ]);
                }
            }
        }

        echo "✅ OLAP sample data for 2024 generated successfully!\n";
    }
}
