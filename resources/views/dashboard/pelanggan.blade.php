@extends('layouts.dashboard')
@section('title', 'Dashboard Pelanggan')

@section('sidebar-menu')
<li>
    <a href="{{ route('dashboard') }}" class="active">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('pelanggan.produk') }}">
        <i class="fas fa-capsules"></i>
        <span>Produk Obat</span>
    </a>
</li>
<li>
    <a href="{{ route('pelanggan.keranjang') }}">
        <i class="fas fa-shopping-cart"></i>
        <span>Keranjang</span>
    </a>
</li>
<li>
    <a href="{{ route('pelanggan.pesanan') }}">
        <i class="fas fa-box"></i>
        <span>Pesanan Saya</span>
    </a>
</li>
<li>
    <a href="{{ route('pelanggan.profil') }}">
        <i class="fas fa-user"></i>
        <span>Profil Saya</span>
    </a>
</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">Dashboard Pelanggan</h2>
        <p class="text-muted">Selamat berbelanja di Apotek kami</p>
    </div>
</div>

<div class="row">
    <!-- Stat Card 1 -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="icon">
                <i class="fas fa-capsules"></i>
            </div>
            <div class="number">150+</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Produk Tersedia</div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="number">0</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Item di Keranjang</div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="number">0</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Pesanan Aktif</div>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="number">0</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Pesanan Selesai</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card-dashboard">
            <h4 class="fw-bold mb-4"><i class="fas fa-bolt text-warning"></i> Aksi Cepat</h4>
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-3">
                    <a href="{{ route('pelanggan.produk') }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <i class="fas fa-shopping-bag d-block mb-2" style="font-size: 2rem;"></i>
                        Belanja Sekarang
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <a href="{{ route('pelanggan.keranjang') }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                        <i class="fas fa-shopping-cart d-block mb-2" style="font-size: 2rem;"></i>
                        Lihat Keranjang
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <a href="{{ route('pelanggan.pesanan') }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                        <i class="fas fa-box d-block mb-2" style="font-size: 2rem;"></i>
                        Cek Pesanan
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <a href="{{ route('pelanggan.profil') }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                        <i class="fas fa-user-circle d-block mb-2" style="font-size: 2rem;"></i>
                        Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Informasi Pelanggan -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card-dashboard">
            <h5 class="fw-bold mb-3"><i class="fas fa-user-circle text-primary"></i> Informasi Akun</h5>
            <table class="table table-borderless">
                <tr>
                    <td class="fw-semibold" width="120">Nama</td>
                    <td>: {{ auth()->user()->name }}</td>
                </tr>
                <tr>
                    <td class="fw-semibold">Username</td>
                    <td>: {{ auth()->user()->username }}</td>
                </tr>
                <tr>
                    <td class="fw-semibold">Email</td>
                    <td>: {{ auth()->user()->email }}</td>
                </tr>
                <tr>
                    <td class="fw-semibold">No. HP</td>
                    <td>: {{ auth()->user()->no_hp }}</td>
                </tr>
                <tr>
                    <td class="fw-semibold">Alamat</td>
                    <td>: {{ auth()->user()->alamat }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card-dashboard">
            <h5 class="fw-bold mb-3"><i class="fas fa-info-circle text-info"></i> Informasi Penting</h5>
            <div class="alert alert-info mb-2">
                <i class="fas fa-shipping-fast"></i> <strong>Pengiriman Gratis</strong> untuk pembelian di atas Rp 100.000
            </div>
            <div class="alert alert-success mb-2">
                <i class="fas fa-certificate"></i> <strong>Produk Original</strong> dan terjamin kualitasnya
            </div>
            <div class="alert alert-warning mb-0">
                <i class="fas fa-clock"></i> <strong>Jam Operasional:</strong> Senin - Sabtu, 08:00 - 20:00 WIB
            </div>
        </div>
    </div>
</div>
@endsection