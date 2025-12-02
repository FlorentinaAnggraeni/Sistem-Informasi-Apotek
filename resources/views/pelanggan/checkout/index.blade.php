@extends('layouts.dashboard')
@section('title', 'Checkout')

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
    <a href="{{ route('pelanggan.keranjang') }}" class="active">
        <i class="fas fa-shopping-cart"></i>
        <span>Keranjang</span>
    </a>
</li>
<li>
    <a href="{{ route('pelanggan.pesanan') }}">
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
        <h2 class="fw-bold">Checkout</h2>
        <p class="text-muted">Konfirmasi pesanan Anda</p>
    </div>
</div>

<form action="{{ route('pelanggan.pesanan.store') }}" method="POST">
    @csrf
    <div class="row">
        <!-- Informasi Pengiriman -->
        <div class="col-md-8">
            <div class="card-dashboard mb-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-shipping-fast text-primary"></i> Informasi Pengiriman</h5>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nama Penerima</label>
                        <input type="text" class="form-control" value="{{ $pelanggan->nama_pelanggan }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">No. Telepon</label>
                        <input type="text" class="form-control" value="{{ $pelanggan->no_telp_pelanggan }}" readonly>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Alamat Pengiriman</label>
                    <textarea class="form-control" rows="3" name="alamat_pengiriman" required>{{ old('alamat_pengiriman', $pelanggan->alamat_pelanggan) }}</textarea>
                    @error('alamat_pengiriman')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Catatan (Opsional)</label>
                    <textarea class="form-control" rows="2" name="catatan" placeholder="Tambahkan catatan untuk pesanan Anda...">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Daftar Pesanan -->
            <div class="card-dashboard">
                <h5 class="fw-bold mb-3"><i class="fas fa-list text-success"></i> Daftar Pesanan</h5>
                
                @foreach($keranjangs as $item)
                    <div class="card mb-2 border">
                        <div class="card-body py-2">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    @if($item->obat->gambar_obat)
                                        <img src="{{ asset('storage/' . $item->obat->gambar_obat) }}" alt="{{ $item->obat->nama_obat }}" class="img-fluid rounded" style="max-height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 60px;">
                                            <i class="fas fa-pills text-secondary"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-5">
                                    <h6 class="mb-0">{{ $item->obat->nama_obat }}</h6>
                                    <small class="text-muted">{{ $item->obat->jenis_obat }}</small>
                                </div>
                                <div class="col-md-2 text-center">
                                    <small class="text-muted">{{ $item->jumlah }} x Rp {{ number_format($item->obat->harga_jual, 0, ',', '.') }}</small>
                                </div>
                                <div class="col-md-3 text-end">
                                    <strong class="text-primary">Rp {{ number_format($item->jumlah * $item->obat->harga_jual, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Ringkasan Pembayaran -->
        <div class="col-md-4">
            <div class="card-dashboard">
                <h5 class="fw-bold mb-3"><i class="fas fa-receipt text-success"></i> Ringkasan Pembayaran</h5>
                
                @php
                    $totalItems = $keranjangs->sum('jumlah');
                    $subtotal = $keranjangs->sum(function($item) {
                        return $item->jumlah * $item->obat->harga_jual;
                    });
                    $ongkir = $subtotal >= 100000 ? 0 : 10000;
                    $totalBayar = $subtotal + $ongkir;
                @endphp

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal ({{ $totalItems }} item)</span>
                        <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Ongkos Kirim</span>
                        <strong>
                            @if($ongkir == 0)
                                <span class="text-success">GRATIS</span>
                            @else
                                Rp {{ number_format($ongkir, 0, ',', '.') }}
                            @endif
                        </strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="mb-0">Total Pembayaran</h5>
                        <h5 class="mb-0 text-primary">Rp {{ number_format($totalBayar, 0, ',', '.') }}</h5>
                    </div>
                </div>

                @if($subtotal < 100000)
                    <div class="alert alert-info small mb-3">
                        <i class="fas fa-info-circle"></i> Belanja minimal Rp 100.000 untuk gratis ongkir
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label fw-bold">Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="form-select" required>
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="transfer">Transfer Bank</option>
                        <option value="e-wallet">E-Wallet (GoPay, OVO, DANA)</option>
                        <option value="qris">QRIS</option>
                    </select>
                    @error('metode_pembayaran')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <input type="hidden" name="total_harga" value="{{ $totalBayar }}">
                <input type="hidden" name="ongkir" value="{{ $ongkir }}">

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-check-circle"></i> Buat Pesanan
                    </button>
                    <a href="{{ route('pelanggan.keranjang') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Keranjang
                    </a>
                </div>

                <div class="alert alert-warning small mt-3 mb-0">
                    <i class="fas fa-exclamation-triangle"></i> Pastikan data pesanan sudah benar sebelum melanjutkan
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('styles')
<style>
    .card-dashboard {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
</style>
@endpush
