@extends('layouts.dashboard')
@section('title', 'Laporan Penjualan')

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
                <h2 class="fw-bold mb-1">Laporan Penjualan</h2>
                <p class="text-muted">Data penjualan dan transaksi periode {{ now()->format('F Y') }}</p>
            </div>
            <a href="{{ route('pemilik.laporan') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card-dashboard mb-4">
    <form method="GET" action="{{ route('pemilik.laporan.penjualan') }}">
        <div class="row">
            <div class="col-md-3">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" 
                       value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" 
                       value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <a href="{{ route('pemilik.laporan.export-excel', ['type' => 'penjualan', 'tanggal_mulai' => $tanggalMulai, 'tanggal_akhir' => $tanggalAkhir]) }}" class="btn btn-success d-block">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card-dashboard" style="border-left: 4px solid #667eea;">
            <small class="text-muted">Total Penjualan</small>
            <h3 class="fw-bold text-primary mb-0">{{ $pesanans->count() }}</h3>
            <small class="text-success"><i class="fas fa-arrow-up"></i> Transaksi</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-dashboard" style="border-left: 4px solid #f093fb;">
            <small class="text-muted">Total Pendapatan</small>
            <h3 class="fw-bold text-success mb-0">Rp {{ number_format($pesanans->sum('total_nota'), 0, ',', '.') }}</h3>
            <small class="text-muted">Periode ini</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-dashboard" style="border-left: 4px solid #4facfe;">
            <small class="text-muted">Rata-rata Transaksi</small>
            <h3 class="fw-bold text-info mb-0">Rp {{ number_format($pesanans->avg('total_nota'), 0, ',', '.') }}</h3>
            <small class="text-muted">Per transaksi</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-dashboard" style="border-left: 4px solid #43e97b;">
            <small class="text-muted">Produk Terlaris</small>
            <h3 class="fw-bold text-warning mb-0">{{ $obatTerlaris->first()->nama_obat ?? '-' }}</h3>
            <small class="text-muted">{{ $obatTerlaris->first()->total_terjual ?? 0 }} terjual</small>
        </div>
    </div>
</div>

<!-- Daftar Transaksi -->
<div class="card-dashboard">
    <h5 class="fw-bold mb-3"><i class="fas fa-receipt text-primary"></i> Daftar Transaksi Penjualan</h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>No. Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Produk</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $index => $pesanan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pesanan->created_at->format('d/m/Y H:i') }}</td>
                    <td><span class="badge bg-secondary">#{{ $pesanan->id_pesanan }}</span></td>
                    <td>
                        <strong>{{ $pesanan->pelanggan->nama_pelanggan }}</strong>
                        <br><small class="text-muted">{{ $pesanan->pelanggan->no_hp_pelanggan }}</small>
                    </td>
                    <td>
                        @foreach($pesanan->detailPesanans->take(2) as $detail)
                            <small>{{ $detail->obat->nama_obat }} ({{ $detail->jumlah }}x)</small><br>
                        @endforeach
                        @if($pesanan->detailPesanans->count() > 2)
                            <small class="text-muted">+{{ $pesanan->detailPesanans->count() - 2 }} lainnya</small>
                        @endif
                    </td>
                    <td><strong>Rp {{ number_format($pesanan->total_nota, 0, ',', '.') }}</strong></td>
                    <td>
                        <span class="badge bg-success">Lunas</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Tidak ada data penjualan</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <th colspan="5" class="text-end">Total Pendapatan:</th>
                    <th colspan="2">Rp {{ number_format($pesanans->sum('total_nota'), 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Produk Terlaris -->
<div class="card-dashboard mt-4">
    <h5 class="fw-bold mb-3"><i class="fas fa-fire text-danger"></i> Top 10 Produk Terlaris</h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Rank</th>
                    <th>Nama Produk</th>
                    <th>Total Terjual</th>
                </tr>
            </thead>
            <tbody>
                @forelse($obatTerlaris as $index => $produk)
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
                    <td><strong>{{ $produk->nama_obat }}</strong></td>
                    <td><span class="badge bg-success">{{ $produk->total_terjual }} unit</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
