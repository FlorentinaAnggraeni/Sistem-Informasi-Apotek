@extends('layouts.dashboard')
@section('title', 'Produk Obat')

@section('sidebar-menu')
<li>
    <a href="{{ route('dashboard') }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('pelanggan.produk') }}" class="active">
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
        <h2 class="fw-bold">Katalog Produk</h2>
        <p class="text-muted">Temukan obat yang Anda butuhkan</p>
    </div>
</div>

<!-- Filter & Search -->
<div class="card-dashboard mb-4">
        <form action="{{ route('pelanggan.produk') }}" method="GET">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Cari Produk</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama obat..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id_kategori }}" {{ request('kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Urutkan</label>
                    <select name="sort" class="form-select">
                        <option value="">Terbaru</option>
                        <option value="harga_asc" {{ request('sort') == 'harga_asc' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="harga_desc" {{ request('sort') == 'harga_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="nama_desc" {{ request('sort') == 'nama_desc' ? 'selected' : '' }}>Nama Z-A</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label d-block">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </div>
        </form>
</div>

<!-- Product Grid -->
@if($obats->count() > 0)
    <div class="row">
        @foreach($obats as $obat)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100 product-card">
                    @if($obat->gambar_obat)
                        <img src="{{ asset('storage/' . $obat->gambar_obat) }}" class="card-img-top" alt="{{ $obat->nama_obat }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-pills fa-4x text-secondary"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        @if($obat->kategori)
                            <span class="badge bg-info mb-2" style="width: fit-content;">{{ $obat->kategori->nama_kategori }}</span>
                        @endif
                        <h5 class="card-title">{{ $obat->nama_obat }}</h5>
                        <p class="card-text text-muted small">{{ $obat->jenis_obat }}</p>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h4 class="text-primary mb-0">Rp {{ number_format($obat->harga_obat, 0, ',', '.') }}</h4>
                                @if($obat->stok_obat < 10)
                                    <span class="badge bg-warning">Stok: {{ $obat->stok_obat }}</span>
                                @else
                                    <span class="badge bg-success">Tersedia</span>
                                @endif
                            </div>
                            <div class="d-grid gap-2">
                                <a href="{{ route('pelanggan.produk.show', $obat) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <form action="{{ route('pelanggan.keranjang.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_obat" value="{{ $obat->id_obat }}">
                                    <input type="hidden" name="jumlah" value="1">
                                    <button type="submit" class="btn btn-sm btn-primary w-100">
                                        <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $obats->links() }}
    </div>
@else
    <div class="card-dashboard text-center py-5">
        <i class="fas fa-search fa-5x text-muted mb-4"></i>
        <h5>Produk Tidak Ditemukan</h5>
        <p class="text-muted mb-4">Coba gunakan kata kunci atau filter yang berbeda</p>
        <a href="{{ route('pelanggan.produk') }}" class="btn btn-primary">Reset Filter</a>
    </div>
@endif
@endsection

@push('styles')
<style>
    .product-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid #dee2e6;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
    }
</style>
@endpush
