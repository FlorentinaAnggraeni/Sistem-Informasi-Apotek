@extends('layouts.dashboard')
@section('title', 'Kelola Supplier')

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
                <h2 class="fw-bold mb-1">Kelola Supplier</h2>
                <p class="text-muted">Manajemen data supplier obat</p>
            </div>
            <a href="{{ route('apoteker.supplier.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Supplier
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

<div class="card-dashboard">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Nama Supplier</th>
                    <th>Kontak</th>
                    <th>Alamat</th>
                    <th>Jumlah Obat</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                <tr>
                    <td>
                        <strong>{{ $supplier->nama_supplier }}</strong><br>
                        @if($supplier->kontak_person)
                        <small class="text-muted">CP: {{ $supplier->kontak_person }}</small>
                        @endif
                    </td>
                    <td>
                        <i class="fas fa-phone text-muted"></i> {{ $supplier->no_telp_supplier }}<br>
                        @if($supplier->email_supplier)
                        <i class="fas fa-envelope text-muted"></i> {{ $supplier->email_supplier }}
                        @endif
                    </td>
                    <td>{{ $supplier->alamat_supplier }}</td>
                    <td>
                        <span class="badge bg-primary">{{ $supplier->obats_count }} Obat</span>
                    </td>
                    <td>
                        <a href="{{ route('apoteker.supplier.show', $supplier->id_supplier) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('apoteker.supplier.edit', $supplier->id_supplier) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('apoteker.supplier.destroy', $supplier->id_supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="fas fa-truck fa-3x mb-3 d-block"></i>
                        Tidak ada data supplier
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-3">
        {{ $suppliers->links() }}
    </div>
</div>
@endsection
