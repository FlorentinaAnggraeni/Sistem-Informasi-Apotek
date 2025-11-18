<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\Apoteker\Karyawan\ObatController;

// Guest Routes
Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dashboard Pelanggan
    Route::middleware('role:pelanggan')->prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/cek-stok', [DashboardController::class, 'cekStok'])->name('cek-stok');
        Route::get('/produk', [DashboardController::class, 'produkPelanggan'])->name('produk');
        Route::get('/pesanan', [DashboardController::class, 'pesanan'])->name('pesanan');
        Route::get('/pembayaran', [DashboardController::class, 'pembayaran'])->name('pembayaran');
    });

    // Dashboard Pemilik
    Route::middleware('role:pemilik')->prefix('pemilik')->name('pemilik.')->group(function () {
        Route::get('/stok-obat', [DashboardController::class, 'stokObat'])->name('stok-obat');
        Route::get('/laporan', [DashboardController::class, 'laporan'])->name('laporan');
    });

    // Dashboard Apoteker / Karyawan
    Route::middleware('role:apoteker,karyawan')
        ->prefix('apoteker/karyawan')
        ->name('apoteker.karyawan.')
        ->group(function () {

            // Menu utama
            Route::get('/kelola-obat', [KaryawanController::class, 'kelolaObat'])->name('kelola-obat');
            Route::get('/transaksi', [KaryawanController::class, 'transaksi'])->name('transaksi');
            Route::get('/pesanan-masuk', [KaryawanController::class, 'pesananMasuk'])->name('pesanan-masuk');

            // CRUD Obat (Controller baru)
            Route::get('/obat', [ObatController::class, 'index'])->name('obat.index');
            Route::post('/obat', [ObatController::class, 'store'])->name('obat.store');
            Route::put('/obat/{id}', [ObatController::class, 'update'])->name('obat.update');
            Route::delete('/obat/{id}', [ObatController::class, 'destroy'])->name('obat.destroy');
        });

});
