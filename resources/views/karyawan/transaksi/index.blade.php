@extends('layouts.dashboard')
@section('title', 'Daftar Transaksi')

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
        <h2 class="fw-bold">Daftar Transaksi</h2>
        <p class="text-muted">Kelola transaksi penjualan</p>
    </div>
</div>
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-cash-register"></i> Daftar Transaksi</h5>
        <div>
            <a href="{{ route('karyawan.transaksi.laporan-harian') }}" class="btn btn-info btn-sm">
                <i class="fas fa-file-alt"></i> Laporan Harian
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($transaksis->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kode Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Karyawan</th>
                            <th>Total</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksis as $index => $transaksi)
                            <tr>
                                <td>{{ $transaksis->firstItem() + $index }}</td>
                                <td>{{ $transaksi->tanggal_transaksi->format('d/m/Y H:i') }}</td>
                                <td>
                                    <code>{{ $transaksi->pesanan->kode_pesanan ?? '-' }}</code>
                                </td>
                                <td>{{ $transaksi->pesanan->pelanggan->nama_pelanggan ?? '-' }}</td>
                                <td>{{ $transaksi->karyawan->nama_karyawan ?? '-' }}</td>
                                <td>
                                    <strong>Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @if($transaksi->metode_pembayaran == 'cash')
                                        <span class="badge bg-success">Cash</span>
                                    @elseif($transaksi->metode_pembayaran == 'transfer')
                                        <span class="badge bg-primary">Transfer</span>
                                    @elseif($transaksi->metode_pembayaran == 'e-wallet')
                                        <span class="badge bg-warning">E-Wallet</span>
                                    @else
                                        <span class="badge bg-info">QRIS</span>
                                    @endif
                                </td>
                                <td>
                                    @if($transaksi->status_transaksi == 'success')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Berhasil
                                        </span>
                                    @elseif($transaksi->status_transaksi == 'pending')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times"></i> Gagal
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('karyawan.transaksi.show', $transaksi) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $transaksis->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">Belum ada transaksi</p>
            </div>
        @endif
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-shopping-cart fa-2x text-primary"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0">Total Transaksi</h6>
                        <h4 class="mb-0">{{ $transaksis->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0">Berhasil</h6>
                        <h4 class="mb-0">{{ $transaksis->where('status_transaksi', 'success')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0">Pending</h6>
                        <h4 class="mb-0">{{ $transaksis->where('status_transaksi', 'pending')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0">Gagal</h6>
                        <h4 class="mb-0">{{ $transaksis->where('status_transaksi', 'failed')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
