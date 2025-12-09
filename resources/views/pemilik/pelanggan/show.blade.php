@extends('layouts.dashboard')
@section('title', 'Detail Pelanggan')

@section('sidebar-menu')
<li>
    <a href="{{ route('dashboard') }}">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('pemilik.laporan') }}">
        <i class="fas fa-chart-line"></i>
        <span>Laporan</span>
    </a>
</li>
<li>
    <a href="{{ route('pemilik.pelanggan.index') }}" class="active">
        <i class="fas fa-users"></i>
        <span>Pelanggan</span>
    </a>
</li>
<li>
    <a href="{{ route('pemilik.karyawan.index') }}">
        <i class="fas fa-user-tie"></i>
        <span>Karyawan</span>
    </a>
</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">Detail Pelanggan</h2>
                <p class="text-muted">Informasi lengkap pelanggan</p>
            </div>
            <div class="gap-2">
                <a href="{{ route('pemilik.pelanggan.edit', $pelanggan) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('pemilik.pelanggan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card-dashboard">
            <h5 class="fw-bold mb-4"><i class="fas fa-user-circle text-primary"></i> Informasi Akun</h5>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="text-muted mb-1">Nama Lengkap</p>
                    <p class="fw-bold">{{ $pelanggan->user->name }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1">Email</p>
                    <p class="fw-bold">{{ $pelanggan->user->email }}</p>
                </div>
            </div>

            <hr class="my-4">

            <h5 class="fw-bold mb-4"><i class="fas fa-id-card text-success"></i> Informasi Pelanggan</h5>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="text-muted mb-1">Nama Pelanggan</p>
                    <p class="fw-bold">{{ $pelanggan->nama_pelanggan }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1">No. Telepon</p>
                    <p class="fw-bold">{{ $pelanggan->no_telp_pelanggan ?? '-' }}</p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="text-muted mb-1">Tanggal Lahir</p>
                    <p class="fw-bold">{{ $pelanggan->tanggal_lahir ? $pelanggan->tanggal_lahir->format('d M Y') : '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1">Jenis Kelamin</p>
                    <p class="fw-bold">
                        @if($pelanggan->jenis_kelamin == 'L')
                            Laki-laki
                        @elseif($pelanggan->jenis_kelamin == 'P')
                            Perempuan
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>

            <div class="mb-3">
                <p class="text-muted mb-1">Alamat</p>
                <p class="fw-bold">{{ $pelanggan->alamat_pelanggan ?? '-' }}</p>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="text-muted mb-1">Bergabung Sejak</p>
                    <p class="fw-bold">{{ $pelanggan->created_at->format('d M Y') }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1">Status</p>
                    <p class="fw-bold"><span class="badge bg-success">Aktif</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-dashboard">
            <h5 class="fw-bold mb-3"><i class="fas fa-shopping-bag text-info"></i> Statistik Pesanan</h5>
            
            <div class="mb-3">
                <p class="text-muted mb-1">Total Pesanan</p>
                <h3 class="fw-bold text-primary">{{ $pelanggan->pesanans->count() ?? 0 }}</h3>
            </div>

            <hr>

            <div class="mb-3">
                <p class="text-muted mb-1">Total Pembelian</p>
                <h4 class="fw-bold">
                    Rp {{ number_format($pelanggan->pesanans->sum('total_nota') ?? 0, 0, ',', '.') }}
                </h4>
            </div>

            @if($pelanggan->pesanans->count() > 0)
                <hr>
                <h6 class="fw-bold mb-2">Pesanan Terakhir</h6>
                @php
                    $pesananTerakhir = $pelanggan->pesanans->sortByDesc('created_at')->first();
                @endphp
                <small class="text-muted d-block mb-1">
                    {{ $pesananTerakhir->created_at->format('d M Y') }}
                </small>
                <small class="text-muted d-block">
                    <span class="badge bg-info">{{ ucfirst($pesananTerakhir->status_pemesanan ?? 'pending') }}</span>
                </small>
            @endif
        </div>

        <div class="card-dashboard mt-3">
            <h5 class="fw-bold mb-3"><i class="fas fa-cog text-secondary"></i> Aksi</h5>
            
            <a href="{{ route('pemilik.pelanggan.edit', $pelanggan) }}" class="btn btn-warning w-100 mb-2">
                <i class="fas fa-edit"></i> Edit Pelanggan
            </a>

            <form action="{{ route('pemilik.pelanggan.destroy', $pelanggan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger w-100">
                    <i class="fas fa-trash"></i> Hapus Pelanggan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
