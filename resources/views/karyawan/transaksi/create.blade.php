@extends('layouts.dashboard')
@section('title', 'Buat Transaksi')

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
        <h2 class="fw-bold">Buat Transaksi Baru</h2>
        <p class="text-muted">Catat transaksi penjualan</p>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-file-invoice"></i> Detail Pesanan</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Kode Pesanan:</strong></p>
                        <p class="text-muted">{{ $pesanan->kode_pesanan }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Tanggal Pesanan:</strong></p>
                        <p class="text-muted">{{ $pesanan->tanggal_pesanan->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Pelanggan:</strong></p>
                        <p class="text-muted">{{ $pesanan->pelanggan->nama_pelanggan }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Status Pembayaran:</strong></p>
                        <span class="badge bg-success">LUNAS</span>
                    </div>
                </div>

                <hr>

                <h6 class="mb-3">Item Pesanan</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Obat</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan->detailPesanans as $detail)
                                <tr>
                                    <td>{{ $detail->obat->nama_obat }}</td>
                                    <td class="text-center">{{ $detail->jumlah }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td colspan="3" class="text-end">TOTAL:</td>
                                <td class="text-end">Rp {{ number_format($pesanan->total_nota, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-cash-register"></i> Form Transaksi</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('karyawan.transaksi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_pesanan" value="{{ $pesanan->id_pesanan }}">

                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                        <select name="metode_pembayaran" class="form-select @error('metode_pembayaran') is-invalid @enderror" required>
                            <option value="">Pilih Metode</option>
                            <option value="cash">Cash / Tunai</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="e-wallet">E-Wallet</option>
                            <option value="qris">QRIS</option>
                        </select>
                        @error('metode_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total Bayar</label>
                        <input type="text" class="form-control fw-bold" value="Rp {{ number_format($pesanan->total_nota, 0, ',', '.') }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Transaksi
                        </button>
                        <a href="{{ route('karyawan.transaksi.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
