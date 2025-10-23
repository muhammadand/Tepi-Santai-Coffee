<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OLAPDataSeeder extends Seeder
{
    private $kuninganData = [
        "Cibingbin" => ["Cibingbin", "Sukaharja", "Sukamaju", "Bunigeulis", "Cisantana", "Pajaran", "Sangiang", "Cisarua"],
        "Kadugede" => ["Kadugede", "Banyusari", "Cihaur", "Cisagar", "Kutamanggu", "Neglasari", "Sukamulya", "Winduraja"],
        "Ciawigebang" => ["Ciawigebang", "Cidahu", "Cikeusal", "Cihaur", "Maja", "Purwawinangun", "Dukuhbadag", "Pajambon"],
        "Cigugur" => ["Cigugur", "Cipari", "Cisantana", "Sukamulya", "Gunungkeling", "Winduherang"],
        "Cilimus" => ["Cilimus", "Cikadu", "Cimara", "Girijaya", "Kaliaren", "Kawungsari", "Sindangjawa", "Trijaya"],
        "Cimahi" => ["Cimahi", "Cibeureum", "Ciherang", "Linggajaya", "Cileuleuy", "Nanggerang", "Paniis", "Rajadanu", "Sidaraja"],
        "Cipicung" => ["Cipicung", "Cihanjuang", "Cikalahang", "Cipari", "Cipasung", "Cisantana", "Mekarsari"],
        "Ciwaru" => ["Ciwaru", "Bojong", "Cilebak", "Cisantana", "Gunungkeling", "Kramat", "Kujang", "Puncak"],
        "Darma" => ["Darma", "Ciomas", "Cicadas", "Kertayasa", "Mandala", "Mekarsari", "Padarama", "Pamijahan", "Sukamukti", "Sukaratu"],
        "Garawangi" => ["Garawangi", "Cimurah", "Bungurberes", "Babakanjaya", "Kertajaya", "Linggasari"],
        "Hantara" => ["Hantara", "Cibunian", "Kalensari", "Mekarjaya", "Pamalayan", "Paninggaran"],
        "Jalaksana" => ["Jalaksana", "Cikahuripan", "Cipangramatan", "Karangsari", "Kertajaya", "Kertasari", "Pajawanlor", "Trijaya"],
        "Japara" => ["Japara", "Ciomas", "Cikubang", "Ciniru", "Cinunuk", "Kalimanggis", "Maniskidul", "Serang", "Sukadana"],
        "Kalimanggis" => ["Kalimanggis", "Ancaran", "Pajawanlor", "Seda", "Sukadana"],
        "Kramatmulya" => ["Kramatmulya", "Cikaso", "Manislor", "Cibentang", "Ciniru", "Karangtawang", "Kertawinangun", "Mekarjaya", "Padawaras"],
        "Kuningan" => ["Kuningan", "Ancaran", "Awirarangan", "Cikumpa", "Ciporang", "Cirendang", "Cijoho", "Purwawinangun", "Winduhaji"],
        "Lebakwangi" => ["Lebakwangi", "Cisangkal", "Kertawinangun", "Sidaraja", "Sukamulya"],
        "Luragung" => ["Luragung", "Cibingbin", "Cibulan", "Cikandang", "Kertawinangun", "Margamulya", "Nanggerang"],
        "Maleber" => ["Maleber", "Cibeureum", "Cigintung", "Jabranti", "Kaliaren", "Pamijahan", "Rawabango", "Simpen"],
        "Mandirancan" => ["Mandirancan", "Cibuntu", "Ciporang", "Kalensari", "Kasturi", "Palutungan", "Sukaraja"],
        "Nusaherang" => ["Nusaherang", "Babakanjati", "Ciakar", "Ciangir", "Cinagara", "Citali", "Pamalayan", "Sampora"],
        "Pancalang" => ["Pancalang", "Bungurberes", "Cibeureum", "Cikubangmulya", "Ciomas", "Cidadap", "Padaherang", "Rangdu"],
        "Pasawahan" => ["Pasawahan", "Buniseuri", "Cibodas", "Cimanggu", "Gunungkeling", "Lemberang", "Pasirmukti", "Rancamulya"],
        "Selajambe" => ["Selajambe", "Babakanreuma", "Cipetir", "Jambar", "Kertamukti", "Pajawankaler", "Sagaranten"],
        "Sindangagung" => ["Sindangagung", "Cigadung", "Ciomas", "Ciporang", "Pajambon", "Sindangsari"],
        "Subang" => ["Subang", "Ciangir", "Cibarelang", "Cibeureujing", "Sukamulya"]
    ];

    public function run(): void
    {
        // --- 1️⃣ USERS ---
        DB::table('users')->insert([
            [
                'name' => 'Admin Cafe',
                'email' => 'admin@cafe.com',
                'role' => 'admin',
                'password' => bcrypt('password'),
                'kabupaten' => 'Kuningan',
                'kecamatan' => 'Kuningan',
                'desa' => 'Cijoho',
                'detail_alamat' => 'Jl. Raya Cafe No. 1, Cijoho, Kuningan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kasir Cafe',
                'email' => 'kasir@cafe.com',
                'role' => 'kasir',
                'password' => bcrypt('password'),
                'kabupaten' => 'Kuningan',
                'kecamatan' => 'Kuningan',
                'desa' => 'Winduhaji',
                'detail_alamat' => 'Jl. Raya Cafe No. 2, Winduhaji, Kuningan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Buat 50 pelanggan dummy dengan distribusi acak ke berbagai kecamatan
        $kecamatanList = array_keys($this->kuninganData);
        
        for ($i = 1; $i <= 50; $i++) {
            $kecamatan = $kecamatanList[array_rand($kecamatanList)];
            $desaList = $this->kuninganData[$kecamatan];
            $desa = $desaList[array_rand($desaList)];
            
            DB::table('users')->insert([
                'name' => "Pelanggan {$i}",
                'email' => "pelanggan{$i}@mail.com",
                'role' => 'pelanggan',
                'password' => bcrypt('password'),
                'kabupaten' => 'Kuningan',
                'kecamatan' => $kecamatan,
                'desa' => $desa,
                'detail_alamat' => "Blok " . chr(65 + ($i % 26)) . " No. {$i}, Desa {$desa}, {$kecamatan}, Kuningan",
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
            ['Espresso', 'Coffee', 18000],
            ['Cappuccino', 'Coffee', 22000],
            ['Latte', 'Coffee', 25000],
            ['Americano', 'Coffee', 20000],
            ['Matcha Latte', 'Non-Coffee', 28000],
            ['Milk Tea', 'Non-Coffee', 23000],
            ['Chocolate', 'Non-Coffee', 24000],
            ['French Fries', 'Snack', 15000],
            ['Kentang Wedges', 'Snack', 18000],
            ['Donut', 'Dessert', 12000],
            ['Brownies', 'Dessert', 20000],
            ['Cheesecake', 'Dessert', 25000],
            ['Nasi Goreng Spesial', 'Special Menu', 30000],
            ['Mie Goreng Spesial', 'Special Menu', 28000],
            ['Spaghetti Carbonara', 'Special Menu', 35000],
        ];

        foreach ($products as [$name, $categoryName, $basePrice]) {
            $categoryId = DB::table('categories')->where('name', $categoryName)->value('id');
            $discount = rand(0, 1) ? rand(1000, 5000) : 0;
            
            DB::table('products')->insert([
                'name' => $name,
                'price' => $basePrice,
                'discount' => $discount,
                'discount_active' => $discount > 0,
                'foto' => null,
                'category_id' => $categoryId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // --- 4️⃣ ORDERS + ORDER ITEMS (1 tahun 2024) ---
        $pelangganIds = DB::table('users')->where('role', 'pelanggan')->pluck('id')->toArray();
        $productList = DB::table('products')->get();

        // Buat data transaksi tiap bulan (Januari - Desember 2024)
        // Variasi jumlah transaksi per bulan untuk simulasi realistis
        $monthlyOrders = [
            1 => 35,  // Januari
            2 => 40,  // Februari
            3 => 45,  // Maret
            4 => 50,  // April
            5 => 55,  // Mei
            6 => 60,  // Juni
            7 => 65,  // Juli
            8 => 70,  // Agustus
            9 => 60,  // September
            10 => 55, // Oktober
            11 => 50, // November
            12 => 80, // Desember (peak season)
        ];

        foreach ($monthlyOrders as $month => $orderCount) {
            for ($i = 1; $i <= $orderCount; $i++) {
                $userId = $pelangganIds[array_rand($pelangganIds)];
                $user = DB::table('users')->where('id', $userId)->first();
                
                // Random tanggal dalam bulan tersebut
                $day = rand(1, 28);
                $hour = rand(8, 21); // Jam operasional 08:00 - 21:00
                $minute = rand(0, 59);
                
                $tanggalOrder = Carbon::create(2024, $month, $day, $hour, $minute)->toDateTimeString();

                $orderId = DB::table('orders')->insertGetId([
                    'user_id' => $userId,
                    'nama' => $user->name,
                    'kabupaten' => $user->kabupaten,
                    'kecamatan' => $user->kecamatan,
                    'desa' => $user->desa,
                    'detail_alamat' => $user->detail_alamat,
                    'payment_status' => 'success',
                    'status_order' => 'selesai',
                    'payment_method' => rand(0, 1) ? 'tunai' : 'non-tunai',
                    'created_at' => $tanggalOrder,
                    'updated_at' => $tanggalOrder,
                ]);

                // Tiap order berisi 1–5 item acak
                $itemCount = rand(1, 5);
                $addedProducts = []; // Track produk yang sudah ditambahkan
                
                for ($j = 0; $j < $itemCount; $j++) {
                    // Pastikan tidak ada produk duplikat dalam satu order
                    do {
                        $product = $productList->random();
                    } while (in_array($product->id, $addedProducts));
                    
                    $addedProducts[] = $product->id;
                    
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
        echo "📊 Total Orders: " . array_sum($monthlyOrders) . "\n";
        echo "👥 Total Pelanggan: 50 pelanggan\n";
        echo "🏘️  Coverage: 26 Kecamatan di Kabupaten Kuningan\n";
    }
}