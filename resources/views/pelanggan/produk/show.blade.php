@extends('layouts.dashboard')
@section('title', 'Detail Produk')

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
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('pelanggan.produk') }}">Produk</a></li>
                <li class="breadcrumb-item active">{{ $obat->nama_obat }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Product Image -->
    <div class="col-md-5 mb-4">
        <div class="card-dashboard">
            @if($obat->gambar_obat)
                <img src="{{ asset('storage/' . $obat->gambar_obat) }}" class="card-img-top" alt="{{ $obat->nama_obat }}" style="height: 400px; object-fit: cover;">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                    <i class="fas fa-pills fa-5x text-secondary"></i>
                </div>
            @endif
        </div>
    </div>

    <!-- Product Details -->
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-body">
                @if($obat->kategori)
                    <span class="badge bg-info mb-2">{{ $obat->kategori->nama_kategori }}</span>
                @endif
                <h2 class="fw-bold">{{ $obat->nama_obat }}</h2>
                <p class="text-muted">{{ $obat->jenis_obat }}</p>
                
                <hr>

                <div class="mb-4">
                    <h3 class="text-primary">Rp {{ number_format($obat->harga_obat, 0, ',', '.') }}</h3>
                    <p class="mb-0">
                        @if($obat->stok_obat > 0)
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle"></i> Stok Tersedia: {{ $obat->stok_obat }}
                            </span>
                        @else
                            <span class="badge bg-danger">
                                <i class="fas fa-times-circle"></i> Stok Habis
                            </span>
                        @endif
                    </p>
                </div>

                @if($obat->deskripsi_obat)
                    <div class="mb-4">
                        <h5>Deskripsi</h5>
                        <p>{{ $obat->deskripsi_obat }}</p>
                    </div>
                @endif

                <div class="mb-4">
                    <h5>Informasi Produk</h5>
                    <table class="table table-sm">
                        <tr>
                            <td width="150">Kategori</td>
                            <td>{{ $obat->kategori->nama_kategori ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Jenis</td>
                            <td>{{ $obat->jenis_obat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Supplier</td>
                            <td>{{ $obat->supplier->nama_supplier ?? '-' }}</td>
                        </tr>
                        @if($obat->no_batch)
                            <tr>
                                <td>No. Batch</td>
                                <td>{{ $obat->no_batch }}</td>
                            </tr>
                        @endif
                        @if($obat->tanggal_kadaluarsa)
                            <tr>
                                <td>Kadaluarsa</td>
                                <td>{{ \Carbon\Carbon::parse($obat->tanggal_kadaluarsa)->format('d/m/Y') }}</td>
                            </tr>
                        @endif
                    </table>
                </div>

                @if($obat->stok_obat > 0)
                    <form action="{{ route('pelanggan.keranjang.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_obat" value="{{ $obat->id_obat }}">
                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Jumlah</label>
                                <input type="number" name="jumlah" class="form-control" value="1" min="1" max="{{ $obat->stok_obat }}" required>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                            </button>
                            <a href="{{ route('pelanggan.produk') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Katalog
                            </a>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Produk ini sedang habis stok
                    </div>
                    <a href="{{ route('pelanggan.produk') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left"></i> Kembali ke Katalog
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Related Products -->
@if(isset($relatedObats) && $relatedObats->count() > 0)
    <div class="mt-5">
        <h4 class="mb-4">Produk Terkait</h4>
        <div class="row">
            @foreach($relatedObats as $related)
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm product-card">
                        @if($related->gambar_obat)
                            <img src="{{ asset('storage/' . $related->gambar_obat) }}" class="card-img-top" alt="{{ $related->nama_obat }}" style="height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                <i class="fas fa-pills fa-3x text-secondary"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h6 class="card-title">{{ $related->nama_obat }}</h6>
                            <p class="text-primary fw-bold">Rp {{ number_format($related->harga_obat, 0, ',', '.') }}</p>
                            <a href="{{ route('pelanggan.produk.show', $related) }}" class="btn btn-sm btn-outline-primary w-100">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
@endsection

@push('styles')
<style>
    .product-card {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
    }
</style>
@endpush
