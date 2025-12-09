@extends('layouts.dashboard')
@section('title', 'Detail Pesanan')

@section('sidebar-menu')
<li>
    <a href="{{ route('dashboard') }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('pelanggan.produk') }}">
        <i class="fas fa-capsules"></i>
        <span>Produk Obat</span>
    </a>
</li>
<li>
    <a href="{{ route('pelanggan.keranjang') }}">
        <i class="fas fa-shopping-cart"></i>
        <span>Keranjang</span>
    </a>
</li>
<li>
    <a href="{{ route('pelanggan.pesanan') }}" class="active">
        <i class="fas fa-box"></i>
        <span>Pesanan Saya</span>
    </a>
</li>
<li>
    <a href="{{ route('pelanggan.resep.index') }}">
        <i class="fas fa-file-prescription"></i>
        <span>Resep Dokter</span>
    </a>
</li>
<li>
    <a href="{{ route('pelanggan.profil') }}">
        <i class="fas fa-user"></i>
        <span>Profil Saya</span>
    </a>
</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">Detail Pesanan</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('pelanggan.pesanan') }}">Pesanan</a></li>
                <li class="breadcrumb-item active">{{ $pesanan->kode_pesanan }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card-dashboard mb-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-info-circle text-primary"></i> Informasi Pesanan</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Kode Pesanan</p>
                        <p class="fw-bold"><code>{{ $pesanan->kode_pesanan }}</code></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Tanggal Pesanan</p>
                        <p class="fw-bold">{{ $pesanan->tanggal_pesanan->format('d F Y, H:i') }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Status Pembayaran</p>
                        <p>
                            @if($pesanan->status_pembayaran == 'paid')
                                <span class="badge bg-success">Lunas</span>
                            @elseif($pesanan->status_pembayaran == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-danger">Belum Bayar</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Status Pengiriman</p>
                        <p>
                            @if($pesanan->status_pengiriman == 'delivered')
                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Selesai</span>
                            @elseif($pesanan->status_pengiriman == 'shipped')
                                <span class="badge bg-primary"><i class="fas fa-shipping-fast"></i> Dikirim</span>
                            @elseif($pesanan->status_pengiriman == 'pending')
                                <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Pending</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($pesanan->status_pengiriman) }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr>

                <h6 class="mb-3">Detail Item</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan->detailPesanans as $detail)
                                <tr>
                                    <td>{{ $detail->obat->nama_obat }}</td>
                                    <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                    <td>{{ $detail->jumlah }}</td>
                                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td colspan="3" class="text-end">TOTAL:</td>
                                <td>Rp {{ number_format($pesanan->total_nota, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-dashboard mb-3">
            <h5 class="fw-bold mb-3"><i class="fas fa-cog text-success"></i> Aksi</h5>
            
            @if($pesanan->status_pembayaran != 'paid' && in_array($pesanan->metode_pembayaran, ['transfer', 'e-wallet', 'qris']))
                <a href="{{ route('pelanggan.pesanan.payment', $pesanan) }}" class="btn btn-warning w-100 mb-2">
                    <i class="fas fa-credit-card"></i> Bayar/Upload Bukti
                </a>
            @endif

            @if($pesanan->status_pengiriman == 'shipped')
                <form action="{{ route('pelanggan.pesanan.konfirmasi', $pesanan) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success w-100 mb-2" onclick="return confirm('Konfirmasi bahwa pesanan telah diterima?')">
                        <i class="fas fa-check"></i> Konfirmasi Penerimaan
                    </button>
                </form>
            @endif

            <a href="{{ route('pelanggan.pesanan') }}" class="btn btn-secondary w-100">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card-dashboard">
            <h5 class="fw-bold mb-3"><i class="fas fa-truck text-info"></i> Status Pengiriman</h5>
            
            @if($pesanan->status_pengiriman == 'shipped' && $pesanan->status_penerimaan != 'diterima')
                <div class="alert alert-primary d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <i class="fas fa-shipping-fast"></i>
                        <strong>Pesanan Sedang Dikirim</strong>
                        @if($pesanan->no_resi)
                            <br><small class="text-muted">No. Resi: <code>{{ $pesanan->no_resi }}</code></small>
                        @endif
                    </div>
                    <form action="{{ route('pelanggan.pesanan.konfirmasi', $pesanan) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('Konfirmasi bahwa pesanan sudah Anda terima?')">
                            <i class="fas fa-check-circle"></i> Terima Pesanan
                        </button>
                    </form>
                </div>
            @endif

            <div class="timeline">
                <div class="timeline-item completed">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong>Pesanan Dibuat</strong>
                        <small class="d-block text-muted">{{ $pesanan->tanggal_pesanan->format('d/m/Y H:i') }}</small>
                    </div>
                </div>
                <div class="timeline-item {{ $pesanan->status_pembayaran == 'paid' ? 'completed' : '' }}">
                    <i class="fas fa-credit-card"></i>
                    <div>
                        <strong>Pembayaran Dikonfirmasi</strong>
                        <small class="d-block text-muted">{{ $pesanan->status_pembayaran == 'paid' ? 'Pembayaran terverifikasi' : 'Menunggu pembayaran' }}</small>
                    </div>
                </div>
                <div class="timeline-item {{ in_array($pesanan->status_pengiriman, ['shipped', 'delivered']) ? 'completed' : '' }}">
                    <i class="fas fa-shipping-fast"></i>
                    <div>
                        <strong>Dikirim</strong>
                        <small class="d-block text-muted">
                            @if($pesanan->status_pengiriman == 'shipped' || $pesanan->status_pengiriman == 'delivered')
                                {{ $pesanan->tanggal_pengiriman ? $pesanan->tanggal_pengiriman->format('d/m/Y H:i') : 'Pesanan dalam perjalanan' }}
                                @if($pesanan->no_resi)
                                    <br>No. Resi: <code>{{ $pesanan->no_resi }}</code>
                                @endif
                            @else
                                Pesanan sedang disiapkan
                            @endif
                        </small>
                    </div>
                </div>
                <div class="timeline-item {{ $pesanan->status_pengiriman == 'delivered' ? 'completed' : '' }}">
                    <i class="fas fa-home"></i>
                    <div>
                        <strong>Selesai</strong>
                        <small class="d-block text-muted">{{ $pesanan->status_pengiriman == 'delivered' ? 'Pesanan telah diterima' : 'Menunggu konfirmasi penerimaan' }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    padding-bottom: 20px;
    padding-left: 30px;
}
.timeline-item:before {
    content: '';
    position: absolute;
    left: -22px;
    top: 0;
    bottom: -20px;
    width: 2px;
    background: #dee2e6;
}
.timeline-item:last-child:before {
    display: none;
}
.timeline-item i {
    position: absolute;
    left: -30px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid #dee2e6;
    color: #dee2e6;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.timeline-item.completed i {
    background: #28a745;
    border-color: #28a745;
    color: #fff;
}
.timeline-item.active i {
    background: #ffc107;
    border-color: #ffc107;
    color: #fff;
}
</style>
@endpush
