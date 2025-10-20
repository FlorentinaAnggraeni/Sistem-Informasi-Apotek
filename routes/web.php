<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

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

    // Dashboard Karyawan
    Route::middleware(['auth', 'role:apoteker,karyawan'])
    ->prefix('apoteker')
    ->name('apoteker.')
    ->group(function () {
        Route::get('/kelola-obat', [DashboardController::class, 'karyawanKelolaObat'])->name('kelola-obat');
        Route::get('/transaksi', [DashboardController::class, 'transaksi'])->name('transaksi');
        Route::get('/pesanan-masuk', [DashboardController::class, 'pesananMasuk'])->name('pesanan-masuk');
    });
});