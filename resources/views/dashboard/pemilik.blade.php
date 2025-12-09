@extends('layouts.dashboard')
@section('title', 'Dashboard Pemilik')

@section('sidebar-menu')
<li>
    <a href="{{ route('dashboard') }}" class="active">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('pemilik.laporan') }}">
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
        <h2 class="fw-bold">Dashboard Pemilik</h2>
        <p class="text-muted">Monitoring dan kontrol sistem apotek</p>
    </div>
</div>

<div class="row">
    <!-- Stat Card 1 -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #00bcd4 0%, #00838f 100%); color: white;">
            <div class="icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="number">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Pendapatan Hari Ini</div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <div class="icon">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="number">{{ $transaksiHariIni }}</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Transaksi Hari Ini</div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="number">{{ $totalPelanggan }}</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Total Pelanggan</div>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
            <div class="icon">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="number">{{ $totalProduk }}</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Total Produk</div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card-dashboard">
            <h5 class="fw-bold mb-3"><i class="fas fa-chart-area text-primary"></i> Grafik Pendapatan Bulanan</h5>
            <canvas id="revenueChart" height="80"></canvas>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card-dashboard">
            <h5 class="fw-bold mb-3"><i class="fas fa-chart-pie text-success"></i> Kategori Produk Terlaris</h5>
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card-dashboard">
            <h5 class="fw-bold mb-3"><i class="fas fa-clock text-warning"></i> Aktivitas Terbaru</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Aktivitas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada aktivitas</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
window.chartData = {
    bulan: @json($bulanLabels),
    pendapatan: @json($pendapatanBulanan),
    kategori: @json($kategoriTerlaris->pluck('nama_kategori')),
    total: @json($kategoriTerlaris->pluck('total'))
};
</script>
<script src="{{ asset('js/pemilik-dashboard.js') }}"></script>
@endpush
