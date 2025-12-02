@extends('layouts.dashboard')
@section('title', 'Resep Dokter Saya')

@section('sidebar-menu')
<li>
    <a href="{{ route('dashboard') }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('pelanggan.produk') }}">
        <i class="fas fa-pills"></i>
        <span>Produk</span>
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
        <span>Pesanan</span>
    </a>
</li>
<li>
    <a href="{{ route('pelanggan.resep.index') }}" class="active">
        <i class="fas fa-file-prescription"></i>
        <span>Resep Dokter</span>
    </a>
</li>
<li>
    <a href="{{ route('pelanggan.profil') }}">
        <i class="fas fa-user"></i>
        <span>Profil</span>
    </a>
</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">Resep Dokter Saya</h2>
                <p class="text-muted">Kelola resep dokter Anda</p>
            </div>
            <a href="{{ route('pelanggan.resep.create') }}" class="btn btn-primary">
                <i class="fas fa-upload"></i> Upload Resep
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

<!-- Filter Tabs -->
<div class="card-dashboard mb-4">
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a class="nav-link {{ request('status') == '' ? 'active' : '' }}" href="{{ route('pelanggan.resep.index') }}">
                Semua ({{ \App\Models\Resep::where('id_pelanggan', Auth::user()->pelanggan->id_pelanggan)->count() }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" href="{{ route('pelanggan.resep.index', ['status' => 'pending']) }}">
                Menunggu ({{ \App\Models\Resep::where('id_pelanggan', Auth::user()->pelanggan->id_pelanggan)->where('status', 'pending')->count() }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') == 'diproses' ? 'active' : '' }}" href="{{ route('pelanggan.resep.index', ['status' => 'diproses']) }}">
                Diproses ({{ \App\Models\Resep::where('id_pelanggan', Auth::user()->pelanggan->id_pelanggan)->where('status', 'diproses')->count() }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') == 'selesai' ? 'active' : '' }}" href="{{ route('pelanggan.resep.index', ['status' => 'selesai']) }}">
                Selesai ({{ \App\Models\Resep::where('id_pelanggan', Auth::user()->pelanggan->id_pelanggan)->where('status', 'selesai')->count() }})
            </a>
        </li>
    </ul>
</div>

<div class="row">
    @forelse($reseps as $resep)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card-dashboard h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="fw-bold mb-1">{{ $resep->no_resep }}</h6>
                    <small class="text-muted">{{ $resep->tanggal_resep->format('d M Y') }}</small>
                </div>
                @if($resep->status == 'pending')
                    <span class="badge bg-warning">Menunggu</span>
                @elseif($resep->status == 'diproses')
                    <span class="badge bg-info">Diproses</span>
                @elseif($resep->status == 'selesai')
                    <span class="badge bg-success">Selesai</span>
                @else
                    <span class="badge bg-danger">Ditolak</span>
                @endif
            </div>

            @if($resep->foto_resep)
            <div class="mb-3">
                <img src="{{ asset('storage/' . $resep->foto_resep) }}" alt="Foto Resep" class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: cover;">
            </div>
            @endif

            <div class="mb-3">
                <p class="mb-1"><strong>Pasien:</strong> {{ $resep->nama_pasien }}</p>
                <p class="mb-1"><strong>Dokter:</strong> {{ $resep->nama_dokter }}</p>
                <p class="mb-0"><strong>Diagnosa:</strong> {{ Str::limit($resep->diagnosa, 50) }}</p>
            </div>

            @if($resep->catatan_apoteker)
            <div class="alert alert-{{ $resep->status == 'ditolak' ? 'danger' : 'info' }} alert-sm">
                <small><strong>Catatan Apoteker:</strong><br>{{ $resep->catatan_apoteker }}</small>
            </div>
            @endif

            <div class="mt-auto">
                <a href="{{ route('pelanggan.resep.show', $resep->id_resep) }}" class="btn btn-sm btn-primary w-100">
                    <i class="fas fa-eye"></i> Lihat Detail
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card-dashboard text-center py-5">
            <i class="fas fa-file-prescription fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada resep</h5>
            <p class="text-muted mb-3">Upload resep dokter Anda untuk mendapatkan obat sesuai anjuran</p>
            <a href="{{ route('pelanggan.resep.create') }}" class="btn btn-primary">
                <i class="fas fa-upload"></i> Upload Resep
            </a>
        </div>
    </div>
    @endforelse
</div>

@if($reseps->hasPages())
<div class="mt-3">
    {{ $reseps->links() }}
</div>
@endif
@endsection
