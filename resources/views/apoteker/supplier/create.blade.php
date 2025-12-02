@extends('layouts.dashboard')
@section('title', 'Tambah Supplier')

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
                <h2 class="fw-bold mb-1">Tambah Supplier</h2>
                <p class="text-muted">Lengkapi informasi supplier baru</p>
            </div>
            <a href="{{ route('apoteker.supplier.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card-dashboard">
            <form action="{{ route('apoteker.supplier.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="nama_supplier" class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_supplier') is-invalid @enderror" id="nama_supplier" name="nama_supplier" value="{{ old('nama_supplier') }}" required autofocus>
                    @error('nama_supplier')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="no_telp_supplier" class="form-label">No. Telepon <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('no_telp_supplier') is-invalid @enderror" id="no_telp_supplier" name="no_telp_supplier" value="{{ old('no_telp_supplier') }}" required>
                        @error('no_telp_supplier')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email_supplier" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email_supplier') is-invalid @enderror" id="email_supplier" name="email_supplier" value="{{ old('email_supplier') }}">
                        @error('email_supplier')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="kontak_person" class="form-label">Kontak Person</label>
                    <input type="text" class="form-control @error('kontak_person') is-invalid @enderror" id="kontak_person" name="kontak_person" value="{{ old('kontak_person') }}">
                    @error('kontak_person')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="alamat_supplier" class="form-label">Alamat <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('alamat_supplier') is-invalid @enderror" id="alamat_supplier" name="alamat_supplier" rows="4" required>{{ old('alamat_supplier') }}</textarea>
                    @error('alamat_supplier')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('apoteker.supplier.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
