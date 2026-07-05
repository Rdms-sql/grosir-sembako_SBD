<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PemesananSupplierController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PemesananKonsumenController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\KonsumenController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\HutangController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\ReturPembelianController;
use App\Http\Controllers\ReturPenjualanController;

//  AUTH  
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// KONSUMEN 
Route::middleware('auth')->prefix('katalog')->name('katalog.')->group(function () {
    Route::get('/', [KatalogController::class, 'index'])->name('index');
    Route::post('/pesan', [KatalogController::class, 'pesan'])->name('pesan');
    Route::get('/riwayat', [KatalogController::class, 'riwayat'])->name('riwayat');
});

//  ADMIN & KASIR
Route::middleware('auth')->group(function () {

    Route::get('/', fn() => redirect('/pemesanan-supplier'));

    // Master
    Route::resource('supplier', SupplierController::class);
    Route::resource('barang', BarangController::class);
    Route::resource('konsumen', KonsumenController::class);

    // Pemesanan Supplier
    Route::resource('pemesanan-supplier', PemesananSupplierController::class);
    Route::patch('pemesanan-supplier/{id}/status', [PemesananSupplierController::class, 'updateStatus'])
         ->name('pemesanan-supplier.updateStatus');

    // Pemesanan Konsumen
    Route::resource('pemesanan-konsumen', PemesananKonsumenController::class);
    Route::patch('pemesanan-konsumen/{id}/status', [PemesananKonsumenController::class, 'updateStatus'])
         ->name('pemesanan-konsumen.updateStatus');

    // Hutang
    Route::get('/hutangs', [HutangController::class, 'index'])->name('hutangs.index');
    Route::get('/hutangs/{id}', [HutangController::class, 'show'])->name('hutangs.show');
    Route::get('/hutangs/{id}/bayar', [HutangController::class, 'bayar'])->name('hutangs.bayar');
    Route::post('/hutangs/{id}/bayar', [HutangController::class, 'simpanBayar'])->name('hutangs.simpan-bayar');

    //Penjualan
    Route::resource('penjualan', PenjualanController::class);

    // Pembelian
    Route::resource('pembelian', PembelianController::class);
    
    //piutang
    Route::get('/piutangs', [PiutangController::class, 'index'])->name('piutangs.index');
    Route::get('/piutangs/{id}', [PiutangController::class, 'show'])->name('piutangs.show');
    Route::get('/piutangs/{id}/terima', [PiutangController::class, 'terima'])->name('piutangs.terima');
    Route::post('/piutangs/{id}/terima', [PiutangController::class, 'simpanTerima'])->name('piutangs.simpan-terima');

    // Retur Penjualan
    Route::get('/retur-penjualan', [ReturPenjualanController::class, 'index'])->name('retur-penjualan.index');
    Route::get('/retur-penjualan/create', [ReturPenjualanController::class, 'create'])->name('retur-penjualan.create');
    Route::get('/retur-penjualan/detail/{id}', [ReturPenjualanController::class, 'getDetailPenjualan'])->name('retur-penjualan.detail');
    Route::post('/retur-penjualan', [ReturPenjualanController::class, 'store'])->name('retur-penjualan.store');
    Route::get('/retur-penjualan/{id}', [ReturPenjualanController::class, 'show'])->name('retur-penjualan.show');
    
    // Retur Pembelian
    Route::get('/retur-pembelian', [ReturPembelianController::class, 'index'])->name('retur-pembelian.index');
    Route::get('/retur-pembelian/create', [ReturPembelianController::class, 'create'])->name('retur-pembelian.create');
    Route::get('/retur-pembelian/detail/{id}', [ReturPembelianController::class, 'getDetailPembelian'])->name('retur-pembelian.detail');
    Route::post('/retur-pembelian', [ReturPembelianController::class, 'store'])->name('retur-pembelian.store');
    Route::get('/retur-pembelian/{id}', [ReturPembelianController::class, 'show'])->name('retur-pembelian.show');

});