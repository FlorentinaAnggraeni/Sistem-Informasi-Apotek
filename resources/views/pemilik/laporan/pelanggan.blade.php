@extends('layouts.dashboard')
@section('title', 'Laporan Pelanggan')

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
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">Laporan Pelanggan</h2>
                <p class="text-muted">Data dan aktivitas pelanggan</p>
            </div>
            <a href="{{ route('pemilik.laporan') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card-dashboard" style="border-left: 4px solid #00bcd4;">
            <small class="text-muted">Total Pelanggan</small>
            <h3 class="fw-bold text-primary mb-0">{{ \App\Models\Pelanggan::count() }}</h3>
            <small class="text-muted">Terdaftar</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-dashboard" style="border-left: 4px solid #f093fb;">
            <small class="text-muted">Pelanggan Aktif</small>
            <h3 class="fw-bold text-success mb-0">{{ \App\Models\Pelanggan::whereHas('pesanans', function($q) { $q->whereMonth('created_at', now()->month); })->count() }}</h3>
            <small class="text-muted">Bulan ini</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-dashboard" style="border-left: 4px solid #4facfe;">
            <small class="text-muted">Pelanggan Baru</small>
            <h3 class="fw-bold text-info mb-0">{{ \App\Models\Pelanggan::whereMonth('created_at', now()->month)->count() }}</h3>
            <small class="text-muted">Bulan ini</small>
        </div>
    </div>
</div>

<!-- Top Pelanggan -->
<div class="card-dashboard mb-4">
    <h5 class="fw-bold mb-3"><i class="fas fa-trophy text-warning"></i> Top 10 Pelanggan Teraktif</h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Rank</th>
                    <th>Nama Pelanggan</th>
                    <th>Kontak</th>
                    <th>Total Transaksi</th>
                    <th>Total Belanja</th>
                    <th>Bergabung</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $topPelanggan = \App\Models\Pelanggan::withCount('pesanans')
                        ->with('pesanans')
                        ->orderBy('pesanans_count', 'desc')
                        ->limit(10)
                        ->get();
                @endphp
                
                @forelse($topPelanggan as $index => $pelanggan)
                <tr>
                    <td>
                        @if($index == 0)
                            <span class="badge bg-warning">🥇</span>
                        @elseif($index == 1)
                            <span class="badge bg-secondary">🥈</span>
                        @elseif($index == 2)
                            <span class="badge bg-info">🥉</span>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </td>
                    <td><strong>{{ $pelanggan->nama_pelanggan }}</strong></td>
                    <td>
                        <small>{{ $pelanggan->email_pelanggan }}</small><br>
                        <small class="text-muted">{{ $pelanggan->no_hp_pelanggan }}</small>
                    </td>
                    <td><span class="badge bg-primary">{{ $pelanggan->pesanans_count }} transaksi</span></td>
                    <td><strong>Rp {{ number_format($pelanggan->pesanans->sum('total_nota'), 0, ',', '.') }}</strong></td>
                    <td>{{ $pelanggan->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Semua Pelanggan -->
<div class="card-dashboard">
    <h5 class="fw-bold mb-3"><i class="fas fa-users text-primary"></i> Semua Pelanggan</h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kontak</th>
                    <th>Alamat</th>
                    <th>Transaksi</th>
                    <th>Bergabung</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $pelangganAll = \App\Models\Pelanggan::withCount('pesanans')
                        ->latest()
                        ->get();
                @endphp
                
                @forelse($pelangganAll as $index => $pel)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $pel->nama_pelanggan }}</strong></td>
                    <td>
                        <small>{{ $pel->email_pelanggan }}</small><br>
                        <small class="text-muted">{{ $pel->no_hp_pelanggan }}</small>
                    </td>
                    <td><small>{{ Str::limit($pel->alamat_pelanggan, 30) }}</small></td>
                    <td><span class="badge bg-info">{{ $pel->pesanans_count }}x</span></td>
                    <td>{{ $pel->created_at->format('d M Y') }}</td>
                    <td>
                        @if($pel->pesanans_count > 0)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Belum Transaksi</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
