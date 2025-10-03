<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Guest routes (belum login)
|--------------------------------------------------------------------------
*/

// ✅ Landing Page diakses oleh semua user
Route::get('/', function () {
    return view('welcome'); // file landing page: resources/views/welcome.blade.php
})->name('landing');

// ✅ Route untuk login & register
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});


/*
|--------------------------------------------------------------------------
| Authenticated routes (sudah login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard berdasarkan role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dashboard Pelanggan
    Route::middleware('role:pelanggan')->prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/produk', [DashboardController::class, 'produkPelanggan'])->name('produk');
        Route::get('/keranjang', [DashboardController::class, 'keranjang'])->name('keranjang');
        Route::get('/pesanan', [DashboardController::class, 'pesanan'])->name('pesanan');
        Route::get('/profil', [DashboardController::class, 'profil'])->name('profil');
    });

    // Dashboard Pemilik
    Route::middleware('role:pemilik')->prefix('pemilik')->name('pemilik.')->group(function () {
        Route::get('/laporan', [DashboardController::class, 'laporan'])->name('laporan');
        Route::get('/kelola-user', [DashboardController::class, 'kelolaUser'])->name('kelola-user');
        Route::get('/pengaturan', [DashboardController::class, 'pengaturan'])->name('pengaturan');
    });

    // Dashboard Apoteker
    Route::middleware('role:apoteker')->prefix('apoteker')->name('apoteker.')->group(function () {
        Route::get('/kelola-obat', [DashboardController::class, 'kelolaObat'])->name('kelola-obat');
        Route::get('/stok', [DashboardController::class, 'stok'])->name('stok');
        Route::get('/resep', [DashboardController::class, 'resep'])->name('resep');
    });

    // Dashboard Karyawan
    Route::middleware('role:karyawan')->prefix('karyawan')->name('karyawan.')->group(function () {
        Route::get('/transaksi', [DashboardController::class, 'transaksi'])->name('transaksi');
        Route::get('/pesanan-masuk', [DashboardController::class, 'pesananMasuk'])->name('pesanan-masuk');
    });
});
