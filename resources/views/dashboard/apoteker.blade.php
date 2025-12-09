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
    <a href="{{ route('apoteker.obat.index') }}">
        <i class="fas fa-pills"></i>
        <span>Kelola Obat</span>
    </a>
</li>
<li>
    <a href="{{ route('apoteker.kategori.index') }}">
        <i class="fas fa-tags"></i>
        <span>Kategori</span>
    </a>
</li>
<li>
    <a href="{{ route('apoteker.supplier.index') }}">
        <i class="fas fa-truck"></i>
        <span>Supplier</span>
    </a>
</li>
<li>
    <a href="{{ route('apoteker.stok') }}">
        <i class="fas fa-warehouse"></i>
        <span>Stok Obat</span>
    </a>
</li>
<li>
    <a href="{{ route('apoteker.resep.index') }}">
        <i class="fas fa-file-prescription"></i>
        <span>Resep Dokter</span>
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
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #00bcd4 0%, #00838f 100%); color: white;">
            <div class="icon">
                <i class="fas fa-capsules"></i>
            </div>
            <div class="number">{{ $totalObat }}</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Total Obat</div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="number">{{ $stokMenurun }}</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Stok Menipis</div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="number">{{ $stokHabis }}</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Stok Habis</div>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
            <div class="icon">
                <i class="fas fa-tags"></i>
            </div>
            <div class="number">{{ $totalKategori }}</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Total Kategori</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card-dashboard">
            <h4 class="fw-bold mb-4"><i class="fas fa-bolt text-warning"></i> Aksi Cepat</h4>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <a href="{{ route('apoteker.obat.create') }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #00bcd4 0%, #00838f 100%); color: white;">
                        <i class="fas fa-plus-circle d-block mb-2" style="font-size: 2rem;"></i>
                        Tambah Obat
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="{{ route('apoteker.resep.index') }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                        <i class="fas fa-file-prescription d-block mb-2" style="font-size: 2rem;"></i>
                        Kelola Resep
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="{{ route('apoteker.stok') }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                        <i class="fas fa-boxes d-block mb-2" style="font-size: 2rem;"></i>
                        Cek Stok
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="{{ route('apoteker.kategori.index') }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                        <i class="fas fa-tags d-block mb-2" style="font-size: 2rem;"></i>
                        Kategori
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stok Alert -->
@if($stokMenurun > 0 || $stokHabis > 0)
<div class="row">
    <div class="col-12 mb-4">
        <div class="card-dashboard">
            <h5 class="fw-bold mb-3"><i class="fas fa-exclamation-triangle text-danger"></i> Peringatan Stok Obat</h5>
            
            @if($stokHabis > 0)
            <div class="alert alert-danger">
                <i class="fas fa-times-circle"></i> 
                <strong>Perhatian!</strong> Ada {{ $stokHabis }} obat dengan stok habis. Segera lakukan restock!
            </div>
            @endif
            
            @if($stokMenurun > 0)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> 
                <strong>Peringatan!</strong> Ada {{ $stokMenurun }} obat dengan stok menipis (< 10).
            </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Obat</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($obatPerluRestock as $obat)
                        <tr>
                            <td><strong>{{ $obat->nama_obat }}</strong></td>
                            <td>{{ $obat->kategori->nama_kategori }}</td>
                            <td>
                                <strong class="{{ $obat->stok_obat == 0 ? 'text-danger' : 'text-warning' }}">
                                    {{ $obat->stok_obat }} {{ $obat->satuan }}
                                </strong>
                            </td>
                            <td>
                                @if($obat->stok_obat == 0)
                                    <span class="badge bg-danger">Habis</span>
                                @else
                                    <span class="badge bg-warning">Menipis</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('apoteker.obat.edit', $obat->id_obat) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Update Stok
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-12 mb-4">
        <div class="card-dashboard">
            <div class="alert alert-success mb-0">
                <i class="fas fa-check-circle"></i> 
                <strong>Bagus!</strong> Semua stok obat dalam kondisi aman.
            </div>
        </div>
    </div>
</div>
@endif
@endsection