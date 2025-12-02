@extends('layouts.dashboard')
@section('title', 'Keranjang Belanja')

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
        <h2 class="fw-bold">Keranjang Belanja</h2>
        <p class="text-muted">Kelola item di keranjang Anda</p>
    </div>
</div>

@if(isset($keranjangs) && $keranjangs->count() > 0)
    <div class="row">
        <div class="col-md-8">
            <div class="card-dashboard">
                <h5 class="fw-bold mb-4"><i class="fas fa-shopping-cart text-primary"></i> Daftar Item</h5>
                
                @foreach($keranjangs as $item)
                    <div class="card mb-3 border">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    @if($item->obat->gambar_obat)
                                        <img src="{{ asset('storage/' . $item->obat->gambar_obat) }}" alt="{{ $item->obat->nama_obat }}" class="img-fluid rounded" style="max-height: 100px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 100px;">
                                            <i class="fas fa-pills fa-2x text-secondary"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-1">{{ $item->obat->nama_obat }}</h6>
                                    <p class="text-muted mb-0 small">{{ $item->obat->jenis_obat }}</p>
                                    <p class="text-primary fw-bold mb-0">Rp {{ number_format($item->obat->harga_obat, 0, ',', '.') }}</p>
                                </div>
                                <div class="col-md-3">
                                    <form action="{{ route('pelanggan.keranjang.update', $item) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="input-group input-group-sm">
                                            <button type="button" class="btn btn-outline-secondary" onclick="decreaseQty(this)">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" name="jumlah" class="form-control text-center" value="{{ $item->jumlah }}" min="1" max="{{ $item->obat->stok_obat }}" onchange="this.form.submit()">
                                            <button type="button" class="btn btn-outline-secondary" data-max="{{ $item->obat->stok_obat }}" onclick="increaseQty(this)">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </form>
                                    <small class="text-muted">Stok: {{ $item->obat->stok_obat }}</small>
                                </div>
                                <div class="col-md-2">
                                    <p class="fw-bold mb-0">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>
                                <div class="col-md-1 text-end">
                                    <form action="{{ route('pelanggan.keranjang.destroy', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus item ini dari keranjang?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('pelanggan.produk') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Lanjut Belanja
                    </a>
                    <form action="{{ route('pelanggan.keranjang.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Kosongkan semua keranjang?')">
                            <i class="fas fa-trash-alt"></i> Kosongkan Keranjang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-dashboard">
                <h5 class="fw-bold mb-3"><i class="fas fa-receipt text-success"></i> Ringkasan Belanja</h5>
                
                @php
                    $totalItems = $keranjangs->sum('jumlah');
                    $subtotal = $keranjangs->sum('subtotal');
                    $ongkir = $subtotal >= 100000 ? 0 : 10000;
                    $total = $subtotal + $ongkir;
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
                        <h5 class="mb-0">Total</h5>
                        <h5 class="mb-0 text-primary">Rp {{ number_format($total, 0, ',', '.') }}</h5>
                    </div>
                </div>

                @if($subtotal < 100000)
                    <div class="alert alert-info small mb-3">
                        <i class="fas fa-info-circle"></i> Belanja Rp {{ number_format(100000 - $subtotal, 0, ',', '.') }} lagi untuk gratis ongkir!
                    </div>
                @endif

                <div class="d-grid">
                    <a href="{{ route('pelanggan.checkout') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-bag"></i> Checkout
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="card-dashboard text-center py-5">
        <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
        <h4>Keranjang Belanja Kosong</h4>
        <p class="text-muted mb-4">Anda belum menambahkan produk apapun</p>
        <a href="{{ route('pelanggan.produk') }}" class="btn btn-primary">
            <i class="fas fa-shopping-bag"></i> Mulai Belanja
        </a>
    </div>
@endif
@endsection

@push('scripts')
<script>
function increaseQty(btn) {
    let max = parseInt(btn.getAttribute('data-max')) || Infinity;
    let input = btn.previousElementSibling;
    let currentVal = parseInt(input.value) || 0;
    if (currentVal < max) {
        input.value = currentVal + 1;
        input.form.submit();
    }
}

function decreaseQty(btn) {
    let input = btn.nextElementSibling;
    let currentVal = parseInt(input.value) || 0;
    if (currentVal > 1) {
        input.value = currentVal - 1;
        input.form.submit();
    }
}
</script>
@endpush
