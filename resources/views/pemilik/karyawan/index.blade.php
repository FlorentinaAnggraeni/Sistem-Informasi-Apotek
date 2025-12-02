@extends('layouts.dashboard')
@section('title', 'Kelola Karyawan')

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
    <a href="{{ route('pemilik.pelanggan.index') }}">
        <i class="fas fa-users"></i>
        <span>Pelanggan</span>
    </a>
</li>
<li>
    <a href="{{ route('pemilik.karyawan.index') }}" class="active">
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
                <h2 class="fw-bold">Kelola Karyawan</h2>
                <p class="text-muted">Manajemen data karyawan dan apoteker</p>
            </div>
            <a href="{{ route('pemilik.karyawan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Karyawan
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card-dashboard">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Role</th>
                    <th>No. Telepon</th>
                    <th>Gaji</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($karyawans as $index => $karyawan)
                <tr>
                    <td>{{ $karyawans->firstItem() + $index }}</td>
                    <td>
                        <strong>{{ $karyawan->nama_karyawan }}</strong>
                        <br><small class="text-muted">{{ $karyawan->user->email }}</small>
                    </td>
                    <td>{{ $karyawan->jabatan }}</td>
                    <td>
                        @if($karyawan->user->role == 'apoteker')
                            <span class="badge bg-success">Apoteker</span>
                        @else
                            <span class="badge bg-info">Karyawan</span>
                        @endif
                    </td>
                    <td>{{ $karyawan->no_telp_karyawan ?? '-' }}</td>
                    <td>
                        @if($karyawan->gaji)
                            Rp {{ number_format($karyawan->gaji, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $karyawan->tanggal_bergabung ? \Carbon\Carbon::parse($karyawan->tanggal_bergabung)->format('d/m/Y') : '-' }}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('pemilik.karyawan.show', $karyawan) }}" class="btn btn-info" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('pemilik.karyawan.edit', $karyawan) }}" class="btn btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('pemilik.karyawan.destroy', $karyawan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus karyawan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                        <p class="text-muted">Belum ada data karyawan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($karyawans->hasPages())
        <div class="mt-3">
            {{ $karyawans->links() }}
        </div>
    @endif
</div>
@endsection
