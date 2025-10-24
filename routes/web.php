<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\TestimoniController;
use App\Http\Controllers\UserController;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('products', ProductController::class);
    Route::post('/products/{id}/set-discount', [ProductController::class, 'setDiscount'])->name('products.setDiscount');

    Route::resource('categories', CategoryController::class);
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/admin/laporan', [DashboardController::class, 'laporan'])->name('laporan.index');
    Route::get('/admin/laporan/export-excel', [DashboardController::class, 'exportExcel'])->name('laporan.export.excel');
    Route::get('/admin/laporan/export-pdf', [DashboardController::class, 'exportPdf'])->name('laporan.export.pdf');
    Route::get('/pesanan-saya', [OrderController::class, 'myOrders'])->name('orders.user');
    //profil
    Route::get('/profil/edit', [UserController::class, 'edit'])->name('profil.edit');
    Route::put('/profil/update', [UserController::class, 'update'])->name('profil.update');
});

Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

Route::resource('users', AuthController::class);



// (Opsional) admin view
Route::get('/admin/feedback', [FeedbackController::class, 'index'])->middleware('auth')->name('feedback.index');
Route::get('/register/admin', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register/admin', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



use App\Http\Controllers\MenuController;

Route::get('/product', [MenuController::class, 'index'])->name('menu.index');

Route::get('/', [MenuController::class, 'landing'])->name('landig');

Route::get('/register', [AuthController::class, 'registeruser'])->name('register.user');

use App\Http\Controllers\CartController;

Route::post('/cart/add/{id}', [CartController::class, 'add']);

Route::post('/cart/update/{id}', [CartController::class, 'update']);
Route::post('/cart/remove/{id}', [CartController::class, 'remove']);
Route::get('/cart', [CartController::class, 'index']);
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

use App\Http\Controllers\CheckoutController;


Route::put('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
Route::delete('/orders/bulk-delete', [OrderController::class, 'bulkDelete'])->name('orders.bulkDelete');
Route::get('/orders/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
Route::get('/orders/{id}/print', [OrderController::class, 'print'])->name('orders.print');
Route::get('/orders/report', [OrderController::class, 'report'])->name('orders.report');


// routes/web.php atau routes/api.php
Route::get('/orders/count-pending', function () {
    $count = \App\Models\Order::where('status_order', 'dipesan')->count();
    return response()->json(['count' => $count]);
})->name('orders.countPending');

Route::get('/orders/latest-pending', function () {
    $order = \App\Models\Order::where('status_order', 'dipesan')
        ->latest('created_at') // atau 'id'
        ->first();

    if ($order) {
        return response()->json([
            'id' => $order->id,
            'nama' => $order->nama, // pastikan field ini ada di database
            'meja' => $order->meja, // pastikan field ini ada juga
            'total' => $order->total // pastikan field ini sesuai
        ]);
    }

    return response()->json(null);
})->name('orders.newOrdersApi');


Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/pembayaran/{id}', [CheckoutController::class, 'pembayaran'])->name('pembayaran');
Route::get('/struk-tunai/{order}', [OrderController::class, 'strukTunai'])->name('struk_tunai');
Route::post('payment/{id}/pay', [OrderController::class, 'pay'])->name('payment.proses');
Route::get('/orders/finish/{id}', [OrderController::class, 'finish'])->name('orders.finish');
Route::put('orders/orders/{order}/status/{status}', [OrderController::class, 'updateStatus1'])->name('orders.updateStatus.status');


Route::get('/orders/{id}', [OrderController::class, 'details'])->name('orders.details');


Route::get('/testimoni/create/{order_item_id}', [TestimoniController::class, 'create'])
->name('testimoni.create');
Route::get('/testimoni', [TestimoniController::class, 'index'])->name('testimoni.index');
// Simpan testimoni ke database
Route::post('/testimoni', [TestimoniController::class, 'store'])
->name('testimoni.store');

Route::get('/layanan/ongkir', [MenuController::class, 'layananOngkir'])->name('layanan.ongkir');
