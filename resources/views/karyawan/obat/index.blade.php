@extends('layouts.dashboard')
@section('title', 'Daftar Obat')

@section('sidebar-menu')
<li>
    <a href="{{ route('dashboard') }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('karyawan.transaksi.index') }}">
        <i class="fas fa-cash-register"></i>
        <span>Transaksi</span>
    </a>
</li>
<li>
    <a href="{{ route('karyawan.pesanan.index') }}">
        <i class="fas fa-inbox"></i>
        <span>Pesanan</span>
    </a>
</li>
<li>
    <a href="{{ route('karyawan.obat.index') }}" class="active">
        <i class="fas fa-pills"></i>
        <span>Daftar Obat</span>
    </a>
</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">Daftar Obat</h2>
        <p class="text-muted">Lihat informasi stok obat</p>
    </div>
</div>
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-pills"></i> Daftar Obat</h5>
    </div>
    <div class="card-body">
        @if(isset($obats) && $obats->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Nama Obat</th>
                            <th>Kategori</th>
                            <th>Jenis</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($obats as $index => $obat)
                            <tr>
                                <td>{{ $obats->firstItem() + $index }}</td>
                                <td>
                                    @if($obat->gambar_obat)
                                        <img src="{{ asset('storage/' . $obat->gambar_obat) }}" alt="{{ $obat->nama_obat }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-pills text-white"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $obat->nama_obat }}</td>
                                <td>{{ $obat->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $obat->jenis_obat ?? '-' }}</td>
                                <td>Rp {{ number_format($obat->harga_obat, 0, ',', '.') }}</td>
                                <td>
                                    @if($obat->stok_obat < 10)
                                        <span class="badge bg-danger">{{ $obat->stok_obat }}</span>
                                    @elseif($obat->stok_obat < 50)
                                        <span class="badge bg-warning">{{ $obat->stok_obat }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $obat->stok_obat }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($obat->stok_obat > 0)
                                        <span class="badge bg-success">Tersedia</span>
                                    @else
                                        <span class="badge bg-danger">Habis</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $obats->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-pills fa-3x text-muted mb-3"></i>
                <p class="text-muted">Belum ada data obat</p>
            </div>
        @endif
    </div>
</div>
@endsection
