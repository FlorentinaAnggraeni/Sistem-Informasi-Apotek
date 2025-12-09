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
    <a href="{{ route('karyawan.transaksi.index') }}">
        <i class="fas fa-cash-register"></i>
        <span>Transaksi</span>
    </a>
</li>
<li>
    <a href="{{ route('karyawan.pesanan.index') }}">
        <i class="fas fa-inbox"></i>
        <span>Pesanan</span>
    </a>
</li>
<li>
    <a href="{{ route('karyawan.obat.index') }}">
        <i class="fas fa-pills"></i>
        <span>Daftar Obat</span>
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
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #00bcd4 0%, #00838f 100%); color: white;">
            <div class="icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="number">{{ $pesananBaru }}</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Pesanan Baru</div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="number">{{ $sedangDiproses }}</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Sedang Diproses</div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="card-dashboard stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="number">{{ $selesaiHariIni }}</div>
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
                <div class="col-md-4 mb-3">
                    <a href="{{ route('karyawan.transaksi.index') }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #00bcd4 0%, #00838f 100%); color: white;">
                        <i class="fas fa-cash-register d-block mb-2" style="font-size: 2rem;"></i>
                        Lihat Transaksi
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="{{ route('karyawan.pesanan.index') }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                        <i class="fas fa-inbox d-block mb-2" style="font-size: 2rem;"></i>
                        Lihat Pesanan
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="{{ route('karyawan.obat.index') }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                        <i class="fas fa-pills d-block mb-2" style="font-size: 2rem;"></i>
                        Daftar Obat
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
                        @forelse($pesananTerbaru as $pesanan)
                        <tr>
                            <td><strong>{{ $pesanan->kode_pesanan }}</strong></td>
                            <td>{{ $pesanan->pelanggan->user->name ?? 'N/A' }}</td>
                            <td><strong>Rp {{ number_format($pesanan->total_nota, 0, ',', '.') }}</strong></td>
                            <td>
                                @if($pesanan->status_pengiriman == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($pesanan->status_pengiriman == 'shipped')
                                    <span class="badge bg-info">Dikirim</span>
                                @elseif($pesanan->status_pengiriman == 'delivered')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('karyawan.pesanan.show', $pesanan->id_pesanan) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada pesanan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection