@extends('layouts.dashboard')
@section('title', 'Dashboard Karyawan')

@section('sidebar-menu')
<li>
    <a href="{{ route('dashboard') }}" class="active">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('karyawan.transaksi') }}">
        <i class="fas fa-cash-register"></i>
        <span>Transaksi</span>
    </a>
</li>
<li>
    <a href="{{ route('karyawan.pesanan-masuk') }}">
        <i class="fas fa-inbox"></i>
        <span>Pesanan Masuk</span>
    </a>
</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">Dashboard Karyawan</h2>
        <p class="text-muted">Kelola transaksi dan pesanan pelanggan</p>
    </div>
</div>

<div class="row">
    <!-- Stat Card 1 -->
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="number">0</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Pesanan Baru</div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="number">0</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Sedang Diproses</div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="number">0</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Selesai Hari Ini</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card-dashboard">
            <h4 class="fw-bold mb-4"><i class="fas fa-bolt text-warning"></i> Aksi Cepat</h4>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <a href="{{ route('karyawan.transaksi') }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <i class="fas fa-cash-register d-block mb-2" style="font-size: 2rem;"></i>
                        Buat Transaksi Baru
                    </a>
                </div>
                <div class="col-md-6 mb-3">
                    <a href="{{ route('karyawan.pesanan-masuk') }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                        <i class="fas fa-inbox d-block mb-2" style="font-size: 2rem;"></i>
                        Lihat Pesanan Masuk
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card-dashboard">
            <h5 class="fw-bold mb-3"><i class="fas fa-list text-primary"></i> Pesanan Terbaru</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada pesanan</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection