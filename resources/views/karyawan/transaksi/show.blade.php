@extends('layouts.dashboard')
@section('title', 'Detail Transaksi')

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
        <h2 class="fw-bold">Detail Transaksi</h2>
        <p class="text-muted">Informasi detail transaksi</p>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-receipt"></i> Informasi Transaksi</h5>
                <a href="{{ route('karyawan.transaksi.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">ID Transaksi</p>
                        <p class="fw-bold">#{{ $transaksi->id_transaksi }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Tanggal Transaksi</p>
                        <p class="fw-bold">{{ $transaksi->tanggal_transaksi->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Kode Pesanan</p>
                        <p class="fw-bold"><code>{{ $transaksi->pesanan->kode_pesanan }}</code></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Metode Pembayaran</p>
                        <p>
                            @if($transaksi->metode_pembayaran == 'cash')
                                <span class="badge bg-success">Cash / Tunai</span>
                            @elseif($transaksi->metode_pembayaran == 'transfer')
                                <span class="badge bg-primary">Transfer Bank</span>
                            @elseif($transaksi->metode_pembayaran == 'e-wallet')
                                <span class="badge bg-warning">E-Wallet</span>
                            @else
                                <span class="badge bg-info">QRIS</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Status Transaksi</p>
                        <p>
                            @if($transaksi->status_transaksi == 'success')
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle"></i> Berhasil
                                </span>
                            @elseif($transaksi->status_transaksi == 'pending')
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle"></i> Gagal
                                </span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Karyawan</p>
                        <p class="fw-bold">{{ $transaksi->karyawan->nama_karyawan ?? '-' }}</p>
                    </div>
                </div>

                @if($transaksi->keterangan)
                    <div class="alert alert-info">
                        <strong>Keterangan:</strong><br>
                        {{ $transaksi->keterangan }}
                    </div>
                @endif

                <hr>

                <h6 class="mb-3">Informasi Pelanggan</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Nama</p>
                        <p class="fw-bold">{{ $transaksi->pesanan->pelanggan->nama_pelanggan }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">No. Telepon</p>
                        <p class="fw-bold">{{ $transaksi->pesanan->pelanggan->no_telepon ?? '-' }}</p>
                    </div>
                </div>

                <hr>

                <h6 class="mb-3">Detail Item Pesanan</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Obat</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksi->pesanan->detailPesanans as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $detail->obat->nama_obat }}</strong>
                                        @if($detail->obat->jenis_obat)
                                            <br><small class="text-muted">{{ $detail->obat->jenis_obat }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $detail->jumlah }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td colspan="4" class="text-end">TOTAL TRANSAKSI:</td>
                                <td class="text-end text-primary">
                                    Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Ringkasan</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <strong>Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Diskon</span>
                    <strong>Rp 0</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total Bayar</strong>
                    <h5 class="text-primary mb-0">Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>

        @if($transaksi->status_transaksi == 'pending')
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-cog"></i> Update Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('karyawan.transaksi.update-status', $transaksi) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label">Status Transaksi</label>
                            <select name="status_transaksi" class="form-select" required>
                                <option value="pending" {{ $transaksi->status_transaksi == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="success" {{ $transaksi->status_transaksi == 'success' ? 'selected' : '' }}>Berhasil</option>
                                <option value="failed" {{ $transaksi->status_transaksi == 'failed' ? 'selected' : '' }}>Gagal</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
