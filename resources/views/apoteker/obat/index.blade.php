@extends('layouts.dashboard')
@section('title', 'Kelola Obat')

@section('sidebar-menu')
<li>
    <a href="{{ route('dashboard') }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('apoteker.obat.index') }}" class="active">
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
                <h2 class="fw-bold mb-1">Kelola Obat</h2>
                <p class="text-muted">Manajemen data obat dan stok</p>
            </div>
            <a href="{{ route('apoteker.obat.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Obat
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
    <div class="row mb-3">
        <div class="col-md-6">
            <form action="{{ route('apoteker.obat.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Cari nama obat..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group">
                <a href="{{ route('apoteker.obat.index') }}" class="btn btn-outline-secondary {{ !request('filter') ? 'active' : '' }}">
                    Semua
                </a>
                <a href="{{ route('apoteker.obat.index', ['filter' => 'habis']) }}" class="btn btn-outline-danger {{ request('filter') == 'habis' ? 'active' : '' }}">
                    Stok Habis
                </a>
                <a href="{{ route('apoteker.obat.index', ['filter' => 'menipis']) }}" class="btn btn-outline-warning {{ request('filter') == 'menipis' ? 'active' : '' }}">
                    Stok Menipis
                </a>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th width="80">Gambar</th>
                    <th>Nama Obat</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Kadaluarsa</th>
                    <th>Status</th>
                    <th width="200">Aksi</th>
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
                    <td>Rp {{ number_format($obat->harga_obat, 0, ',', '.') }}</td>
                    <td>
                        <strong class="{{ $obat->stok_obat == 0 ? 'text-danger' : ($obat->stok_obat < 10 ? 'text-warning' : 'text-success') }}">
                            {{ $obat->stok_obat }} {{ $obat->satuan }}
                        </strong>
                    </td>
                    <td>
                        @if($obat->tanggal_kadaluarsa)
                            {{ \Carbon\Carbon::parse($obat->tanggal_kadaluarsa)->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($obat->stok_obat == 0)
                            <span class="badge bg-danger">Habis</span>
                        @elseif($obat->stok_obat < 10)
                            <span class="badge bg-warning">Menipis</span>
                        @else
                            <span class="badge bg-success">Tersedia</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('apoteker.obat.edit', $obat->id_obat) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('apoteker.obat.destroy', $obat->id_obat) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus obat ini?')">
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
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
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
