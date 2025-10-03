@extends('layouts.dashboard')
@section('title', 'Dashboard Apoteker')

@section('sidebar-menu')
<li>
    <a href="{{ route('dashboard') }}" class="active">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('apoteker.kelola-obat') }}">
        <i class="fas fa-pills"></i>
        <span>Kelola Obat</span>
    </a>
</li>
<li>
    <a href="{{ route('apoteker.stok') }}">
        <i class="fas fa-warehouse"></i>
        <span>Manajemen Stok</span>
    </a>
</li>
<li>
    <a href="{{ route('apoteker.resep') }}">
        <i class="fas fa-prescription"></i>
        <span>Validasi Resep</span>
    </a>
</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">Dashboard Apoteker</h2>
        <p class="text-muted">Manajemen obat dan validasi resep</p>
    </div>
</div>

<div class="row">
    <!-- Stat Card 1 -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="icon">
                <i class="fas fa-capsules"></i>
            </div>
            <div class="number">0</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Total Obat</div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="number">0</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Stok Menipis</div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <div class="icon">
                <i class="fas fa-prescription"></i>
            </div>
            <div class="number">0</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Resep Pending</div>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
            <div class="icon">
                <i class="fas fa-calendar-times"></i>
            </div>
            <div class="number">0</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Obat Kadaluarsa</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card-dashboard">
            <h4 class="fw-bold mb-4"><i class="fas fa-bolt text-warning"></i> Aksi Cepat</h4>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <a href="{{ route('apoteker.kelola-obat') }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <i class="fas fa-plus-circle d-block mb-2" style="font-size: 2rem;"></i>
                        Tambah Obat Baru
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="{{ route('apoteker.resep') }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                        <i class="fas fa-file-prescription d-block mb-2" style="font-size: 2rem;"></i>
                        Validasi Resep
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="{{ route('apoteker.stok') }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                        <i class="fas fa-boxes d-block mb-2" style="font-size: 2rem;"></i>
                        Cek Stok Obat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alerts -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card-dashboard">
            <h5 class="fw-bold mb-3"><i class="fas fa-bell text-danger"></i> Peringatan Stok</h5>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> Tidak ada obat dengan stok menipis
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card-dashboard">
            <h5 class="fw-bold mb-3"><i class="fas fa-calendar-alt text-info"></i> Obat Mendekati Kadaluarsa</h5>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Tidak ada obat yang mendekati kadaluarsa
            </div>
        </div>
    </div>
</div>
@endsection