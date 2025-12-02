@extends('layouts.dashboard')
@section('title', 'Kelola Resep')

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
    <a href="{{ route('apoteker.stok') }}">
        <i class="fas fa-warehouse"></i>
        <span>Stok Obat</span>
    </a>
</li>
<li>
    <a href="{{ route('apoteker.resep.index') }}" class="active">
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
                <h2 class="fw-bold mb-1">Kelola Resep Dokter</h2>
                <p class="text-muted">Validasi dan proses resep dari pelanggan</p>
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

<!-- Filter Tabs -->
<div class="card-dashboard mb-4">
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a class="nav-link {{ request('status') == '' ? 'active' : '' }}" href="{{ route('apoteker.resep.index') }}">
                Semua ({{ \App\Models\Resep::count() }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" href="{{ route('apoteker.resep.index', ['status' => 'pending']) }}">
                Pending ({{ \App\Models\Resep::where('status', 'pending')->count() }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') == 'diproses' ? 'active' : '' }}" href="{{ route('apoteker.resep.index', ['status' => 'diproses']) }}">
                Diproses ({{ \App\Models\Resep::where('status', 'diproses')->count() }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') == 'selesai' ? 'active' : '' }}" href="{{ route('apoteker.resep.index', ['status' => 'selesai']) }}">
                Selesai ({{ \App\Models\Resep::where('status', 'selesai')->count() }})
            </a>
        </li>
    </ul>
</div>

<div class="card-dashboard">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>No. Resep</th>
                    <th>Tanggal</th>
                    <th>Pasien</th>
                    <th>Dokter</th>
                    <th>Pelanggan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $query = request('status') ? $reseps->where('status', request('status')) : $reseps;
                @endphp
                
                @forelse($reseps as $resep)
                <tr>
                    <td><span class="badge bg-secondary">{{ $resep->no_resep }}</span></td>
                    <td>{{ $resep->tanggal_resep->format('d/m/Y') }}</td>
                    <td><strong>{{ $resep->nama_pasien }}</strong></td>
                    <td>{{ $resep->nama_dokter }}</td>
                    <td>
                        {{ $resep->pelanggan->nama_pelanggan }}<br>
                        <small class="text-muted">{{ $resep->pelanggan->no_telp_pelanggan }}</small>
                    </td>
                    <td>
                        @if($resep->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($resep->status == 'diproses')
                            <span class="badge bg-info">Diproses</span>
                        @elseif($resep->status == 'selesai')
                            <span class="badge bg-success">Selesai</span>
                        @else
                            <span class="badge bg-danger">Ditolak</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('apoteker.resep.show', $resep->id_resep) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        @if($resep->status == 'pending')
                        <a href="{{ route('apoteker.resep.proses', $resep->id_resep) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-check"></i> Proses
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                        Tidak ada data resep
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-3">
        {{ $reseps->links() }}
    </div>
</div>
@endsection
