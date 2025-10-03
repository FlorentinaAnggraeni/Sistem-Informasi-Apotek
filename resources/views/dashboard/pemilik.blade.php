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
        <span>Laporan Keuangan</span>
    </a>
</li>
<li>
    <a href="{{ route('pemilik.kelola-user') }}">
        <i class="fas fa-users-cog"></i>
        <span>Kelola User</span>
    </a>
</li>
<li>
    <a href="{{ route('pemilik.pengaturan') }}">
        <i class="fas fa-cog"></i>
        <span>Pengaturan</span>
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
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="number">Rp 0</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Pendapatan Hari Ini</div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <div class="icon">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="number">0</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Transaksi Hari Ini</div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="number">0</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Total Pelanggan</div>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
            <div class="icon">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="number">0</div>
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
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Pendapatan (Juta Rupiah)',
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    });

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['Obat Resep', 'Obat Bebas', 'Vitamin', 'Alat Kesehatan'],
            datasets: [{
                data: [0, 0, 0, 0],
                backgroundColor: ['#667eea', '#f093fb', '#4facfe', '#43e97b']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    });
</script>
@endpush