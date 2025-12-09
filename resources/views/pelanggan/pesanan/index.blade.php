@extends('layouts.dashboard')
@section('title', 'Pesanan Saya')

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
        <h2 class="fw-bold">Pesanan Saya</h2>
        <p class="text-muted">Kelola dan pantau status pesanan Anda</p>
    </div>
</div>

<div class="card-dashboard">
        @if(isset($pesanans) && $pesanans->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Pesanan</th>
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
                                <td>{{ $pesanan->tanggal_pesanan->format('d/m/Y H:i') }}</td>
                                <td><strong>Rp {{ number_format($pesanan->total_nota, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if($pesanan->status_pembayaran == 'paid')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Lunas
                                        </span>
                                    @elseif($pesanan->status_pembayaran == 'pending')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle"></i> Belum Bayar
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($pesanan->status_pengiriman == 'delivered')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Selesai
                                        </span>
                                    @elseif($pesanan->status_pengiriman == 'shipped')
                                        <span class="badge bg-primary">
                                            <i class="fas fa-shipping-fast"></i> Dikirim
                                        </span>
                                    @elseif($pesanan->status_pengiriman == 'pending')
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    @elseif($pesanan->status_pengiriman == 'cancelled')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle"></i> Dibatalkan
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($pesanan->status_pengiriman) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('pelanggan.pesanan.show', $pesanan) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    @if($pesanan->status_pembayaran != 'paid' && in_array($pesanan->metode_pembayaran, ['transfer', 'e-wallet', 'qris']))
                                        <a href="{{ route('pelanggan.pesanan.payment', $pesanan) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-credit-card"></i> Bayar/Upload
                                        </a>
                                    @endif
                                    @if($pesanan->status_pengiriman == 'shipped' && $pesanan->status_penerimaan != 'diterima')
                                        <form action="{{ route('pelanggan.pesanan.konfirmasi', $pesanan) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi bahwa pesanan sudah Anda terima?')">
                                                <i class="fas fa-check"></i> Terima
                                            </button>
                                        </form>
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
                <i class="fas fa-box-open fa-5x text-muted mb-4"></i>
                <h4>Belum Ada Pesanan</h4>
                <p class="text-muted mb-4">Anda belum melakukan pemesanan apapun</p>
                <a href="{{ route('pelanggan.produk') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i> Mulai Belanja
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
