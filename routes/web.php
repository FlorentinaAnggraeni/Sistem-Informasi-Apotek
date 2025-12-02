<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ResepController;

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

    // Notifications untuk semua user yang login
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
    });

    // Dashboard Pelanggan
    Route::middleware('role:pelanggan')->prefix('pelanggan')->name('pelanggan.')->group(function () {
        // Produk
        Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
        Route::get('/produk/{obat}', [ProdukController::class, 'show'])->name('produk.show');
        
        // Keranjang
        Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang');
        Route::post('/keranjang', [KeranjangController::class, 'store'])->name('keranjang.store');
        Route::put('/keranjang/{keranjang}', [KeranjangController::class, 'update'])->name('keranjang.update');
        Route::delete('/keranjang/{keranjang}', [KeranjangController::class, 'destroy'])->name('keranjang.destroy');
        Route::delete('/keranjang', [KeranjangController::class, 'clear'])->name('keranjang.clear');
        
        // Checkout & Pesanan
        Route::get('/checkout', [PesananController::class, 'checkout'])->name('checkout');
        Route::post('/pesanan', [PesananController::class, 'store'])->name('pesanan.store');
        Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan');
        Route::get('/pesanan/{pesanan}', [PesananController::class, 'show'])->name('pesanan.show');
        Route::get('/pesanan/{pesanan}/payment', [PesananController::class, 'payment'])->name('pesanan.payment');
        Route::post('/pesanan/{pesanan}/upload-bukti', [PesananController::class, 'uploadBukti'])->name('pesanan.upload-bukti');
        Route::post('/pesanan/{pesanan}/konfirmasi', [PesananController::class, 'konfirmasiPenerimaan'])->name('pesanan.konfirmasi');
        
        // Profil
        Route::get('/profil', [DashboardController::class, 'profil'])->name('profil');
        Route::put('/profil', [DashboardController::class, 'updateProfil'])->name('profil.update');
        
        // Resep Dokter
        Route::get('/resep', [ResepController::class, 'pelangganIndex'])->name('resep.index');
        Route::get('/resep/create', [ResepController::class, 'create'])->name('resep.create');
        Route::post('/resep', [ResepController::class, 'pelangganStore'])->name('resep.store');
        Route::get('/resep/{id}', [ResepController::class, 'pelangganShow'])->name('resep.show');
    });

    // Dashboard Pemilik
    Route::middleware('role:pemilik')->prefix('pemilik')->name('pemilik.')->group(function () {
        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
        Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
        Route::get('/laporan/stok', [LaporanController::class, 'stok'])->name('laporan.stok');
        Route::get('/laporan/pelanggan', [LaporanController::class, 'pelanggan'])->name('laporan.pelanggan');
        Route::get('/laporan/keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');
        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPDF'])->name('laporan.export-pdf');
        Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export-excel');
        
        // Kelola User - Pelanggan
        Route::resource('pelanggan', PelangganController::class);
        
        // Kelola User - Karyawan
        Route::resource('karyawan', KaryawanController::class);
        
        Route::get('/pengaturan', [DashboardController::class, 'pengaturan'])->name('pengaturan');
    });

    // Dashboard Apoteker
    Route::middleware('role:apoteker')->prefix('apoteker')->name('apoteker.')->group(function () {
        // Kelola Obat
        Route::resource('obat', ObatController::class);
        
        // Kelola Kategori
        Route::resource('kategori', KategoriController::class)->except(['show']);
        
        // Kelola Supplier
        Route::resource('supplier', SupplierController::class);
        
        // Stok
        Route::get('/stok', [ObatController::class, 'stok'])->name('stok');
        Route::put('/stok/{obat}', [ObatController::class, 'updateStok'])->name('stok.update');
        
        // Resep
        Route::get('/resep', [ResepController::class, 'index'])->name('resep.index');
        Route::get('/resep/{id}', [ResepController::class, 'show'])->name('resep.show');
        Route::get('/resep/{id}/proses', [ResepController::class, 'proses'])->name('resep.proses');
        Route::put('/resep/{id}', [ResepController::class, 'updateStatus'])->name('resep.updateStatus');
    });

    // Dashboard Karyawan
    Route::middleware('role:karyawan')->prefix('karyawan')->name('karyawan.')->group(function () {
        // Transaksi
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/transaksi/laporan-harian', [TransaksiController::class, 'laporanHarian'])->name('transaksi.laporan-harian');
        Route::get('/transaksi/create/{pesanan}', [TransaksiController::class, 'create'])->name('transaksi.create');
        Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::get('/transaksi/{transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');
        Route::patch('/transaksi/{transaksi}/status', [TransaksiController::class, 'updateStatus'])->name('transaksi.update-status');
        
        // Pesanan
        Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/{pesanan}', [PesananController::class, 'show'])->name('pesanan.show');
        Route::patch('/pesanan/{pesanan}/status', [PesananController::class, 'updateStatus'])->name('pesanan.update-status');
        Route::post('/pesanan/{pesanan}/verify-payment', [PesananController::class, 'verifyPayment'])->name('pesanan.verify-payment');
        Route::post('/pesanan/{pesanan}/verifikasi', [PesananController::class, 'verifyPayment'])->name('pesanan.verifikasi');
        
        // Obat (read-only for karyawan)
        Route::get('/obat', [ObatController::class, 'index'])->name('obat.index');
        Route::get('/obat/{obat}', [ObatController::class, 'show'])->name('obat.show');
    });
});
