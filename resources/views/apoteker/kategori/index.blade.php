@extends('layouts.dashboard')
@section('title', 'Kelola Kategori')

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
    <a href="{{ route('apoteker.kategori.index') }}" class="active">
        <i class="fas fa-tags"></i>
        <span>Kategori</span>
    </a>
</li>
<li>
    <a href="{{ route('apoteker.supplier.index') }}">
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
                <h2 class="fw-bold mb-1">Kelola Kategori</h2>
                <p class="text-muted">Manajemen kategori obat</p>
            </div>
            <a href="{{ route('apoteker.kategori.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Kategori
            </a>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    @forelse($kategoris as $kategori)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card-dashboard h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h5 class="fw-bold mb-1">{{ $kategori->nama_kategori }}</h5>
                    <span class="badge bg-primary">{{ $kategori->obats_count }} Obat</span>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('apoteker.kategori.edit', $kategori->id_kategori) }}">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('apoteker.kategori.destroy', $kategori->id_kategori) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            
            @if($kategori->deskripsi_kategori)
            <p class="text-muted small mb-0">{{ $kategori->deskripsi_kategori }}</p>
            @else
            <p class="text-muted small mb-0 fst-italic">Tidak ada deskripsi</p>
            @endif
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card-dashboard text-center py-5">
            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada kategori</h5>
            <p class="text-muted">Silakan tambah kategori baru</p>
            <a href="{{ route('apoteker.kategori.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Kategori
            </a>
        </div>
    </div>
    @endforelse
</div>

@if($kategoris->hasPages())
<div class="mt-3">
    {{ $kategoris->links() }}
</div>
@endif
@endsection
