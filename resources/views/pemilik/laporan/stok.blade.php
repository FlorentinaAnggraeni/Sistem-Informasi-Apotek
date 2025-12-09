@extends('layouts.dashboard')
@section('title', 'Laporan Stok')

@section('sidebar-menu')
<li>
    <a href="{{ route('dashboard') }}">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('pemilik.laporan') }}" class="active">
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
                <h2 class="fw-bold mb-1">Laporan Stok Obat</h2>
                <p class="text-muted">Monitoring stok dan ketersediaan obat</p>
            </div>
            <a href="{{ route('pemilik.laporan') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card-dashboard" style="background: linear-gradient(135deg, #00bcd4 0%, #00838f 100%); color: white;">
            <div class="icon" style="font-size: 2rem; opacity: 0.3;">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="number">{{ \App\Models\Obat::count() }}</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Total Produk</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-dashboard" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <div class="icon" style="font-size: 2rem; opacity: 0.3;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="number">{{ \App\Models\Obat::where('stok_obat', '<', 10)->count() }}</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Stok Menipis</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-dashboard" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <div class="icon" style="font-size: 2rem; opacity: 0.3;">
                <i class="fas fa-ban"></i>
            </div>
            <div class="number">{{ \App\Models\Obat::where('stok_obat', '=', 0)->count() }}</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Stok Habis</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-dashboard" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
            <div class="icon" style="font-size: 2rem; opacity: 0.3;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="number">{{ \App\Models\Obat::where('stok_obat', '>=', 10)->count() }}</div>
            <div class="label" style="color: rgba(255,255,255,0.9);">Stok Aman</div>
        </div>
    </div>
</div>

<!-- Alert Stok Menipis -->
@if(\App\Models\Obat::where('stok_obat', '<', 10)->count() > 0)
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i> 
    <strong>Perhatian!</strong> Ada {{ \App\Models\Obat::where('stok_obat', '<', 10)->count() }} produk dengan stok menipis. 
    Segera lakukan restock!
</div>
@endif

<!-- Filter -->
<div class="card-dashboard mb-4">
    <form method="GET" action="{{ route('pemilik.laporan.stok') }}">
        <div class="row">
            <div class="col-md-3">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach(\App\Models\Kategori::all() as $kat)
                        <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status Stok</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="habis">Habis (0)</option>
                    <option value="menipis">Menipis (< 10)</option>
                    <option value="aman">Aman (>= 10)</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <a href="{{ route('pemilik.laporan.export-excel', ['type' => 'stok']) }}" class="btn btn-success d-block">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Tabel Stok -->
<div class="card-dashboard">
    <h5 class="fw-bold mb-3"><i class="fas fa-warehouse text-primary"></i> Data Stok Obat</h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Obat</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Total Nilai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $query = \App\Models\Obat::with('kategori');
                    
                    if(request('kategori')) {
                        $query->where('id_kategori', request('kategori'));
                    }
                    
                    if(request('status') == 'habis') {
                        $query->where('stok_obat', 0);
                    } elseif(request('status') == 'menipis') {
                        $query->where('stok_obat', '<', 10)->where('stok_obat', '>', 0);
                    } elseif(request('status') == 'aman') {
                        $query->where('stok_obat', '>=', 10);
                    }
                    
                    $obats = $query->orderBy('stok_obat', 'asc')->get();
                @endphp
                
                @forelse($obats as $index => $obat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><span class="badge bg-secondary">{{ $obat->no_batch ?? '-' }}</span></td>
                    <td><strong>{{ $obat->nama_obat }}</strong></td>
                    <td>{{ $obat->kategori->nama_kategori }}</td>
                    <td>
                        <strong class="{{ $obat->stok_obat == 0 ? 'text-danger' : ($obat->stok_obat < 10 ? 'text-warning' : 'text-success') }}">
                            {{ $obat->stok_obat }} {{ $obat->satuan }}
                        </strong>
                    </td>
                    <td>Rp {{ number_format($obat->harga_obat, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($obat->harga_obat * $obat->stok_obat, 0, ',', '.') }}</td>
                    <td>
                        @if($obat->stok_obat == 0)
                            <span class="badge bg-danger">Habis</span>
                        @elseif($obat->stok_obat < 10)
                            <span class="badge bg-warning">Menipis</span>
                        @else
                            <span class="badge bg-success">Aman</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <th colspan="6" class="text-end">Total Nilai Stok:</th>
                    <th colspan="2">Rp {{ number_format($obats->sum(function($o) { return $o->harga_obat * $o->stok_obat; }), 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
