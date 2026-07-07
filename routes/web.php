<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PemesananSupplierController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PemesananKonsumenController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\KonsumenController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\HutangController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\PembelianController;
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
Route::middleware(['auth', 'role:konsumen'])->prefix('katalog')->name('katalog.')->group(function () {
    Route::get('/', [KatalogController::class, 'index'])->name('index');
    Route::post('/pesan', [KatalogController::class, 'pesan'])->name('pesan');
    Route::get('/riwayat', [KatalogController::class, 'riwayat'])->name('riwayat');
});

//  ADMIN & KASIR
Route::middleware(['auth', 'role:admin,kasir'])->group(function () {

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

    // Piutang
    Route::get('/piutangs', [PiutangController::class, 'index'])->name('piutangs.index');
    Route::get('/piutangs/{id}', [PiutangController::class, 'show'])->name('piutangs.show');
    Route::get('/piutangs/{id}/terima', [PiutangController::class, 'terima'])->name('piutangs.terima');
    Route::post('/piutangs/{id}/terima', [PiutangController::class, 'simpanTerima'])->name('piutangs.simpan-terima');

    // Penjualan
    Route::resource('penjualan', PenjualanController::class);

    // Pembelian
    Route::resource('pembelian', PembelianController::class);

    // ReturPembelian
    Route::resource('retur-pembelian', ReturPembelianController::class);

    // ReturPenjualan
    Route::resource('retur-penjualan', ReturPenjualanController::class);
});
