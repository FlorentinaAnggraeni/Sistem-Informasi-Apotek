@extends('layouts.dashboard')
@section('title', 'Laporan')

@section('sidebar-menu')
<li>
    <a href="{{ route('dashboard') }}">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('pemilik.laporan') }}" class="active">
        <i class="fas fa-chart-line"></i>
        <span>Laporan</span>
    </a>
</li>
<li>
    <a href="{{ route('pemilik.pelanggan.index') }}">
        <i class="fas fa-users"></i>
        <span>Pelanggan</span>
    </a>
</li>
<li>
    <a href="{{ route('pemilik.karyawan.index') }}">
        <i class="fas fa-user-tie"></i>
        <span>Karyawan</span>
    </a>
</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">Laporan</h2>
        <p class="text-muted">Kelola dan monitor berbagai laporan sistem</p>
    </div>
</div>

<div class="row">
    <!-- Laporan Penjualan -->
    <div class="col-md-6 col-lg-3 mb-4">
        <a href="{{ route('pemilik.laporan.penjualan') }}" class="text-decoration-none">
            <div class="card-dashboard h-100 text-center hover-shadow" style="cursor: pointer;">
                <div class="mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-chart-line fa-2x text-primary"></i>
                    </div>
                </div>
                <h5 class="fw-bold text-dark">Laporan Penjualan</h5>
                <p class="text-muted small">Data penjualan dan transaksi</p>
            </div>
        </a>
    </div>

    <!-- Laporan Stok -->
    <div class="col-md-6 col-lg-3 mb-4">
        <a href="{{ route('pemilik.laporan.stok') }}" class="text-decoration-none">
            <div class="card-dashboard h-100 text-center hover-shadow" style="cursor: pointer;">
                <div class="mb-3">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-boxes fa-2x text-success"></i>
                    </div>
                </div>
                <h5 class="fw-bold text-dark">Laporan Stok</h5>
                <p class="text-muted small">Monitoring stok obat</p>
            </div>
        </a>
    </div>

    <!-- Laporan Pelanggan -->
    <div class="col-md-6 col-lg-3 mb-4">
        <a href="{{ route('pemilik.laporan.pelanggan') }}" class="text-decoration-none">
            <div class="card-dashboard h-100 text-center hover-shadow" style="cursor: pointer;">
                <div class="mb-3">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                </div>
                <h5 class="fw-bold text-dark">Laporan Pelanggan</h5>
                <p class="text-muted small">Data pelanggan aktif</p>
            </div>
        </a>
    </div>

    <!-- Laporan Keuangan -->
    <div class="col-md-6 col-lg-3 mb-4">
        <a href="{{ route('pemilik.laporan.keuangan') }}" class="text-decoration-none">
            <div class="card-dashboard h-100 text-center hover-shadow" style="cursor: pointer;">
                <div class="mb-3">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-money-bill-wave fa-2x text-warning"></i>
                    </div>
                </div>
                <h5 class="fw-bold text-dark">Laporan Keuangan</h5>
                <p class="text-muted small">Ringkasan keuangan</p>
            </div>
        </a>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card-dashboard">
            <h5 class="fw-bold mb-4"><i class="fas fa-chart-bar text-primary"></i> Ringkasan Cepat</h5>
            <div class="row text-center">
                <div class="col-md-3 mb-3">
                    <h3 class="text-primary mb-0">{{ \App\Models\Transaksi::whereMonth('created_at', now()->month)->count() }}</h3>
                    <small class="text-muted">Transaksi Bulan Ini</small>
                </div>
                <div class="col-md-3 mb-3">
                    <h3 class="text-success mb-0">Rp {{ number_format(\App\Models\Transaksi::whereMonth('created_at', now()->month)->sum('total_transaksi'), 0, ',', '.') }}</h3>
                    <small class="text-muted">Pendapatan Bulan Ini</small>
                </div>
                <div class="col-md-3 mb-3">
                    <h3 class="text-info mb-0">{{ \App\Models\Pelanggan::count() }}</h3>
                    <small class="text-muted">Total Pelanggan</small>
                </div>
                <div class="col-md-3 mb-3">
                    <h3 class="text-warning mb-0">{{ \App\Models\Obat::where('stok_obat', '<', 10)->count() }}</h3>
                    <small class="text-muted">Stok Menipis</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}
</style>
@endpush
