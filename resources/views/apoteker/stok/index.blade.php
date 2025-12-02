@extends('layouts.dashboard')
@section('title', 'Stok Obat')

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
    <a href="{{ route('apoteker.supplier.index') }}">
        <i class="fas fa-truck"></i>
        <span>Supplier</span>
    </a>
</li>
<li>
    <a href="{{ route('apoteker.stok') }}" class="active">
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
                <h2 class="fw-bold mb-1">Monitoring Stok Obat</h2>
                <p class="text-muted">Pantau dan kelola stok obat</p>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card-dashboard" style="border-left: 4px solid #dc3545;">
            <h6 class="text-muted mb-2">Stok Habis</h6>
            <h3 class="fw-bold text-danger mb-0">{{ $obats->where('stok_obat', 0)->count() }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-dashboard" style="border-left: 4px solid #ffc107;">
            <h6 class="text-muted mb-2">Stok Menipis (< 10)</h6>
            <h3 class="fw-bold text-warning mb-0">{{ $obats->whereBetween('stok_obat', [1, 9])->count() }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-dashboard" style="border-left: 4px solid #28a745;">
            <h6 class="text-muted mb-2">Stok Aman (≥ 10)</h6>
            <h3 class="fw-bold text-success mb-0">{{ $obats->where('stok_obat', '>=', 10)->count() }}</h3>
        </div>
    </div>
</div>

<div class="card-dashboard">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th width="80">Gambar</th>
                    <th>Nama Obat</th>
                    <th>Kategori</th>
                    <th>Supplier</th>
                    <th>Stok Saat Ini</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($obats as $obat)
                <tr>
                    <td>
                        @if($obat->gambar_obat)
                        <img src="{{ asset('storage/' . $obat->gambar_obat) }}" alt="{{ $obat->nama_obat }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-pills text-muted"></i>
                        </div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $obat->nama_obat }}</strong><br>
                        <small class="text-muted">{{ $obat->jenis_obat }}</small>
                    </td>
                    <td>{{ $obat->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $obat->supplier->nama_supplier ?? '-' }}</td>
                    <td>
                        <strong class="{{ $obat->stok_obat == 0 ? 'text-danger' : ($obat->stok_obat < 10 ? 'text-warning' : 'text-success') }}">
                            {{ $obat->stok_obat }} {{ $obat->satuan }}
                        </strong>
                    </td>
                    <td>
                        @if($obat->stok_obat == 0)
                            <span class="badge bg-danger">Habis</span>
                        @elseif($obat->stok_obat < 10)
                            <span class="badge bg-warning">Menipis</span>
                        @else
                            <span class="badge bg-success">Aman</span>
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateStokModal{{ $obat->id_obat }}">
                            <i class="fas fa-edit"></i> Update Stok
                        </button>
                    </td>
                </tr>

                <!-- Modal Update Stok -->
                <div class="modal fade" id="updateStokModal{{ $obat->id_obat }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Update Stok: {{ $obat->nama_obat }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('apoteker.stok.update', $obat->id_obat) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Stok saat ini: <strong>{{ $obat->stok_obat }} {{ $obat->satuan }}</strong>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="stok_obat" class="form-label">Stok Baru</label>
                                        <input type="number" class="form-control" id="stok_obat" name="stok_obat" value="{{ $obat->stok_obat }}" min="0" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                        Tidak ada data obat
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-3">
        {{ $obats->links() }}
    </div>
</div>
@endsection
