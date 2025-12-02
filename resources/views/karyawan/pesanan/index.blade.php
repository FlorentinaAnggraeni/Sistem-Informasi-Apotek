@extends('layouts.dashboard')
@section('title', 'Daftar Pesanan')

@section('sidebar-menu')
<li>
    <a href="{{ route('dashboard') }}">
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
    <a href="{{ route('karyawan.pesanan.index') }}" class="active">
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
        <h2 class="fw-bold">Daftar Pesanan</h2>
        <p class="text-muted">Kelola pesanan pelanggan</p>
    </div>
</div>
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Daftar Pesanan</h5>
    </div>
    <div class="card-body">
        @if(isset($pesanans) && $pesanans->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status Pembayaran</th>
                            <th>Status Pesanan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesanans as $index => $pesanan)
                            <tr>
                                <td>{{ $pesanans->firstItem() + $index }}</td>
                                <td><code>{{ $pesanan->kode_pesanan }}</code></td>
                                <td>{{ $pesanan->pelanggan->nama_pelanggan ?? '-' }}</td>
                                <td>{{ $pesanan->tanggal_pesanan->format('d/m/Y H:i') }}</td>
                                <td><strong>Rp {{ number_format($pesanan->total_nota, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if($pesanan->status_pembayaran == 'paid')
                                        <span class="badge bg-success">Lunas</span>
                                    @elseif($pesanan->status_pembayaran == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Belum Bayar</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pesanan->status_pesanan == 'completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($pesanan->status_pesanan == 'processing')
                                        <span class="badge bg-info">Diproses</span>
                                    @elseif($pesanan->status_pesanan == 'shipped')
                                        <span class="badge bg-primary">Dikirim</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($pesanan->status_pesanan) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('karyawan.pesanan.show', $pesanan) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($pesanan->status_pembayaran == 'paid' && $pesanan->status_pesanan != 'completed')
                                        <a href="{{ route('karyawan.transaksi.create', $pesanan) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-cash-register"></i> Proses
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $pesanans->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">Belum ada pesanan</p>
            </div>
        @endif
    </div>
</div>
@endsection
