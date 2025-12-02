@extends('layouts.dashboard')
@section('title', 'Laporan Harian Transaksi')

@section('sidebar-menu')
<li>
    <a href="{{ route('dashboard') }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('karyawan.transaksi.index') }}" class="active">
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
        <h2 class="fw-bold">Laporan Harian Transaksi</h2>
        <p class="text-muted">Laporan transaksi hari ini</p>
    </div>
</div>
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-chart-line"></i> Laporan Transaksi Hari Ini</h5>
        <div>
            <a href="{{ route('karyawan.transaksi.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="fas fa-print"></i> Cetak
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="text-center mb-4">
            <h4>APOTEK</h4>
            <p class="mb-0">Laporan Transaksi Harian</p>
            <p class="text-muted">{{ now()->format('d F Y') }}</p>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <i class="fas fa-shopping-cart fa-2x text-primary mb-2"></i>
                        <h6 class="text-muted">Jumlah Transaksi</h6>
                        <h3 class="mb-0">{{ $jumlahTransaksi }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <i class="fas fa-money-bill-wave fa-2x text-success mb-2"></i>
                        <h6 class="text-muted">Total Pendapatan</h6>
                        <h3 class="mb-0 text-success">Rp {{ number_format($totalTransaksi, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-info">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-bar fa-2x text-info mb-2"></i>
                        <h6 class="text-muted">Rata-rata Transaksi</h6>
                        <h3 class="mb-0 text-info">
                            Rp {{ number_format($jumlahTransaksi > 0 ? $totalTransaksi / $jumlahTransaksi : 0, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        @if($transaksis->count() > 0)
            <h6 class="mb-3">Detail Transaksi</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Waktu</th>
                            <th>Kode Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Karyawan</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksis as $index => $transaksi)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $transaksi->tanggal_transaksi->format('H:i') }}</td>
                                <td><code>{{ $transaksi->pesanan->kode_pesanan ?? '-' }}</code></td>
                                <td>{{ $transaksi->pesanan->pelanggan->nama_pelanggan ?? '-' }}</td>
                                <td>{{ $transaksi->karyawan->nama_karyawan ?? '-' }}</td>
                                <td>
                                    @if($transaksi->metode_pembayaran == 'cash')
                                        Cash
                                    @elseif($transaksi->metode_pembayaran == 'transfer')
                                        Transfer
                                    @elseif($transaksi->metode_pembayaran == 'e-wallet')
                                        E-Wallet
                                    @else
                                        QRIS
                                    @endif
                                </td>
                                <td>
                                    @if($transaksi->status_transaksi == 'success')
                                        <span class="badge bg-success">Berhasil</span>
                                    @elseif($transaksi->status_transaksi == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Gagal</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($transaksi->status_transaksi == 'success')
                                        Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr class="fw-bold">
                            <td colspan="7" class="text-end">TOTAL PENDAPATAN:</td>
                            <td class="text-end text-success">Rp {{ number_format($totalTransaksi, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Payment Method Breakdown -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <h6>Rekap Per Metode Pembayaran</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Metode Pembayaran</th>
                                    <th class="text-center">Jumlah Transaksi</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $metodePembayaran = ['cash' => 'Cash', 'transfer' => 'Transfer', 'e-wallet' => 'E-Wallet', 'qris' => 'QRIS'];
                                @endphp
                                @foreach($metodePembayaran as $key => $label)
                                    @php
                                        $filtered = $transaksis->where('metode_pembayaran', $key)->where('status_transaksi', 'success');
                                        $count = $filtered->count();
                                        $total = $filtered->sum('total_transaksi');
                                    @endphp
                                    <tr>
                                        <td>{{ $label }}</td>
                                        <td class="text-center">{{ $count }}</td>
                                        <td class="text-end">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-5 pt-4 border-top">
                <div class="row">
                    <div class="col-6 text-center">
                        <p class="mb-5">Mengetahui,</p>
                        <p class="mb-0">_________________</p>
                        <p class="mb-0"><small>Kepala Apotek</small></p>
                    </div>
                    <div class="col-6 text-center">
                        <p class="mb-5">Dibuat oleh,</p>
                        <p class="mb-0">_________________</p>
                        <p class="mb-0"><small>{{ Auth::user()->name }}</small></p>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i> Tidak ada transaksi pada hari ini
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    @media print {
        .sidebar, .btn, .card-header .btn, .alert {
            display: none !important;
        }
        .content-wrapper {
            margin: 0 !important;
            padding: 0 !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush
@endsection
