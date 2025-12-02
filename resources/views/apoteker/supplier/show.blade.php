@extends('layouts.dashboard')
@section('title', 'Detail Supplier')

@section('sidebar-menu')
<li>
    <a href="{{ route('dashboard') }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('apoteker.obat.index') }}">
        <i class="fas fa-pills"></i>
        <span>Kelola Obat</span>
    </a>
</li>
<li>
    <a href="{{ route('apoteker.kategori.index') }}">
        <i class="fas fa-tags"></i>
        <span>Kategori</span>
    </a>
</li>
<li>
    <a href="{{ route('apoteker.supplier.index') }}" class="active">
        <i class="fas fa-truck"></i>
        <span>Supplier</span>
    </a>
</li>
<li>
    <a href="{{ route('apoteker.stok') }}">
        <i class="fas fa-warehouse"></i>
        <span>Stok Obat</span>
    </a>
</li>
<li>
    <a href="{{ route('apoteker.resep.index') }}">
        <i class="fas fa-file-prescription"></i>
        <span>Resep Dokter</span>
    </a>
</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">Detail Supplier</h2>
                <p class="text-muted">{{ $supplier->nama_supplier }}</p>
            </div>
            <div>
                <a href="{{ route('apoteker.supplier.edit', $supplier->id_supplier) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('apoteker.supplier.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card-dashboard">
            <h5 class="fw-bold mb-4">Informasi Supplier</h5>
            
            <div class="mb-3">
                <label class="text-muted small">Nama Supplier</label>
                <p class="fw-bold mb-0">{{ $supplier->nama_supplier }}</p>
            </div>

            <div class="mb-3">
                <label class="text-muted small">No. Telepon</label>
                <p class="mb-0">
                    <i class="fas fa-phone text-primary"></i> {{ $supplier->no_telp_supplier }}
                </p>
            </div>

            @if($supplier->email_supplier)
            <div class="mb-3">
                <label class="text-muted small">Email</label>
                <p class="mb-0">
                    <i class="fas fa-envelope text-primary"></i> {{ $supplier->email_supplier }}
                </p>
            </div>
            @endif

            @if($supplier->kontak_person)
            <div class="mb-3">
                <label class="text-muted small">Kontak Person</label>
                <p class="mb-0">{{ $supplier->kontak_person }}</p>
            </div>
            @endif

            <div class="mb-3">
                <label class="text-muted small">Alamat</label>
                <p class="mb-0">{{ $supplier->alamat_supplier }}</p>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                <strong>{{ $supplier->obats->count() }}</strong> obat dari supplier ini
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card-dashboard">
            <h5 class="fw-bold mb-4">Daftar Obat</h5>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Obat</th>
                            <th>Jenis</th>
                            <th>Stok</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supplier->obats as $obat)
                        <tr>
                            <td><strong>{{ $obat->nama_obat }}</strong></td>
                            <td>{{ $obat->jenis_obat }}</td>
                            <td>
                                <span class="{{ $obat->stok_obat < 10 ? 'text-danger' : 'text-success' }}">
                                    {{ $obat->stok_obat }} {{ $obat->satuan }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($obat->harga_obat, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Belum ada obat dari supplier ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
