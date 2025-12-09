@extends('layouts.dashboard')
@section('title', 'Laporan Keuangan')

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
                <h2 class="fw-bold mb-1">Laporan Keuangan</h2>
                <p class="text-muted">Ringkasan keuangan periode {{ now()->format('F Y') }}</p>
            </div>
            <a href="{{ route('pemilik.laporan') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<!-- Filter Periode -->
<div class="card-dashboard mb-4">
    <form method="GET" action="{{ route('pemilik.laporan.keuangan') }}">
        <div class="row">
            <div class="col-md-3">
                <label class="form-label">Pilih Bulan</label>
                <select name="bulan" class="form-select">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('bulan', now()->month) == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Pilih Tahun</label>
                <select name="tahun" class="form-select">
                    @for($y = now()->year; $y >= now()->year - 3; $y--)
                        <option value="{{ $y }}" {{ request('tahun', now()->year) == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">
                    <i class="fas fa-search"></i> Tampilkan
                </button>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <a href="#" class="btn btn-success d-block">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>
        </div>
    </form>
</div>

@php
    $bulan = request('bulan', now()->month);
    $tahun = request('tahun', now()->year);
    
    // Total Pemasukan (dari transaksi)
    $pemasukan = \App\Models\Transaksi::whereYear('created_at', $tahun)
        ->whereMonth('created_at', $bulan)
        ->where('status_transaksi', 'success')
        ->sum('total_transaksi');
    
    // Total Pengeluaran (estimasi dari pembelian stok - bisa disesuaikan)
    // Untuk sementara kita hitung dari harga beli obat yang terjual
    $pengeluaran = \App\Models\DetailPesanan::whereHas('pesanan', function($q) use ($bulan, $tahun) {
            $q->whereYear('created_at', $tahun)
              ->whereMonth('created_at', $bulan)
              ->where('status_pembayaran', 'paid');
        })
        ->with('obat')
        ->get()
        ->sum(function($detail) {
            return ($detail->obat->harga_beli ?? 0) * $detail->jumlah;
        });
    
    $labaKotor = $pemasukan - $pengeluaran;
    
    // Transaksi harian
    $transaksiHarian = \App\Models\Transaksi::whereYear('created_at', $tahun)
        ->whereMonth('created_at', $bulan)
        ->where('status_transaksi', 'success')
        ->selectRaw('DATE(created_at) as tanggal, SUM(total_transaksi) as total')
        ->groupBy('tanggal')
        ->orderBy('tanggal')
        ->get();
@endphp

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card-dashboard" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
            <div class="icon" style="font-size: 2rem; opacity: 0.3;">
                <i class="fas fa-arrow-down"></i>
            </div>
            <div class="number">Rp {{ number_format($pemasukan, 0, ',', '.') }}</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Total Pemasukan</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-dashboard" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <div class="icon" style="font-size: 2rem; opacity: 0.3;">
                <i class="fas fa-arrow-up"></i>
            </div>
            <div class="number">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Total Pengeluaran</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-dashboard" style="background: linear-gradient(135deg, #00bcd4 0%, #00838f 100%); color: white;">
            <div class="icon" style="font-size: 2rem; opacity: 0.3;">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="number">Rp {{ number_format($labaKotor, 0, ',', '.') }}</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Laba Kotor</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-dashboard" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <div class="icon" style="font-size: 2rem; opacity: 0.3;">
                <i class="fas fa-percentage"></i>
            </div>
            <div class="number">{{ $pemasukan > 0 ? number_format(($labaKotor / $pemasukan) * 100, 1) : 0 }}%</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Margin Keuntungan</div>
        </div>
    </div>
</div>

<!-- Grafik Transaksi Harian -->
<div class="card-dashboard mb-4">
    <h5 class="fw-bold mb-3"><i class="fas fa-chart-area text-primary"></i> Grafik Pemasukan Harian</h5>
    <canvas id="dailyRevenueChart" height="80"></canvas>
</div>

<!-- Rincian Transaksi -->
<div class="row">
    <div class="col-md-6">
        <div class="card-dashboard">
            <h5 class="fw-bold mb-3"><i class="fas fa-cash-register text-success"></i> Rincian Pemasukan</h5>
            <table class="table">
                <tr>
                    <td>Total Transaksi</td>
                    <td class="text-end">{{ \App\Models\Transaksi::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->count() }} transaksi</td>
                </tr>
                <tr>
                    <td>Transaksi Berhasil</td>
                    <td class="text-end">{{ \App\Models\Transaksi::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->where('status_transaksi', 'success')->count() }} transaksi</td>
                </tr>
                <tr>
                    <td>Rata-rata per Transaksi</td>
                    <td class="text-end">Rp {{ number_format(\App\Models\Transaksi::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->where('status_transaksi', 'success')->avg('total_transaksi') ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr class="table-light">
                    <th>Total Pemasukan</th>
                    <th class="text-end text-success">Rp {{ number_format($pemasukan, 0, ',', '.') }}</th>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card-dashboard">
            <h5 class="fw-bold mb-3"><i class="fas fa-shopping-cart text-danger"></i> Rincian Pengeluaran</h5>
            <table class="table">
                <tr>
                    <td>Modal Produk Terjual</td>
                    <td class="text-end">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Biaya Operasional</td>
                    <td class="text-end"><span class="text-muted">-</span></td>
                </tr>
                <tr>
                    <td>Lain-lain</td>
                    <td class="text-end"><span class="text-muted">-</span></td>
                </tr>
                <tr class="table-light">
                    <th>Total Pengeluaran</th>
                    <th class="text-end text-danger">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</th>
                </tr>
            </table>
        </div>
    </div>
</div>

<!-- Ringkasan Laba Rugi -->
<div class="card-dashboard mt-4">
    <h5 class="fw-bold mb-3"><i class="fas fa-calculator text-warning"></i> Ringkasan Laba Rugi</h5>
    <table class="table table-bordered">
        <tr>
            <th width="50%">Pemasukan</th>
            <th class="text-end">Rp {{ number_format($pemasukan, 0, ',', '.') }}</th>
        </tr>
        <tr>
            <td>Pengeluaran</td>
            <td class="text-end text-danger">(Rp {{ number_format($pengeluaran, 0, ',', '.') }})</td>
        </tr>
        <tr class="table-light">
            <th>Laba Bersih</th>
            <th class="text-end {{ $labaKotor >= 0 ? 'text-success' : 'text-danger' }}">
                Rp {{ number_format($labaKotor, 0, ',', '.') }}
            </th>
        </tr>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
window.transaksiData = {
    labels: @json($transaksiHarian->pluck('tanggal')->map(fn($t) => \Carbon\Carbon::parse($t)->format('d M'))),
    data: @json($transaksiHarian->pluck('total'))
};
</script>
<script src="{{ asset('js/laporan-keuangan.js') }}"></script>
@endpush
