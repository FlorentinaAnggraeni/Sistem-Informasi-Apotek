@extends('layouts.dashboard')
@section('title', 'Detail Resep')

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
                <h2 class="fw-bold mb-1">Detail Resep</h2>
                <p class="text-muted">{{ $resep->no_resep }}</p>
            </div>
            <a href="{{ route('pelanggan.resep.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card-dashboard mb-4">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h5 class="fw-bold mb-2">Informasi Resep</h5>
                    <p class="text-muted mb-0">{{ $resep->no_resep }}</p>
                </div>
                @if($resep->status == 'pending')
                    <span class="badge bg-warning">Menunggu Validasi</span>
                @elseif($resep->status == 'diproses')
                    <span class="badge bg-info">Sedang Diproses</span>
                @elseif($resep->status == 'selesai')
                    <span class="badge bg-success">Selesai</span>
                @else
                    <span class="badge bg-danger">Ditolak</span>
                @endif
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="text-muted mb-1">Tanggal Resep</p>
                    <p class="fw-bold">{{ $resep->tanggal_resep->format('d F Y') }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1">Status</p>
                    <p class="fw-bold text-capitalize">{{ $resep->status }}</p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="text-muted mb-1">Nama Pasien</p>
                    <p class="fw-bold">{{ $resep->nama_pasien }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1">Nama Dokter</p>
                    <p class="fw-bold">{{ $resep->nama_dokter }}</p>
                </div>
            </div>

            <div class="mb-3">
                <p class="text-muted mb-1">Diagnosa</p>
                <p>{{ $resep->diagnosa }}</p>
            </div>

            @if($resep->foto_resep)
            <div class="mb-3">
                <p class="text-muted mb-2">Foto Resep</p>
                <img src="{{ asset('storage/' . $resep->foto_resep) }}" alt="Foto Resep" class="img-fluid rounded border">
            </div>
            @endif

            @if($resep->catatan_apoteker)
            <div class="alert alert-{{ $resep->status == 'ditolak' ? 'danger' : 'info' }}">
                <h6 class="alert-heading"><i class="fas fa-{{ $resep->status == 'ditolak' ? 'times-circle' : 'info-circle' }}"></i> Catatan Apoteker</h6>
                <p class="mb-0">{{ $resep->catatan_apoteker }}</p>
            </div>
            @endif

            @if($resep->apoteker)
            <div class="alert alert-light">
                <i class="fas fa-user-md"></i> <strong>Divalidasi oleh:</strong> {{ $resep->apoteker->nama_karyawan }} ({{ $resep->apoteker->jabatan }})
            </div>
            @endif
        </div>

        @if($resep->detailReseps->count() > 0)
        <div class="card-dashboard">
            <h5 class="fw-bold mb-4">Obat yang Diresepkan</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Obat</th>
                            <th>Jumlah</th>
                            <th>Aturan Pakai</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resep->detailReseps as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $detail->obat->nama_obat }}</strong></td>
                            <td>{{ $detail->jumlah }} {{ $detail->obat->satuan }}</td>
                            <td><span class="badge bg-primary">{{ $detail->aturan_pakai }}</span></td>
                            <td>{{ $detail->catatan ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card-dashboard">
            <h6 class="fw-bold mb-3">Status Resep</h6>
            
            <div class="timeline">
                <div class="timeline-item {{ in_array($resep->status, ['pending', 'diproses', 'selesai', 'ditolak']) ? 'active' : '' }}">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <strong>Resep Diupload</strong>
                        <p class="text-muted small mb-0">{{ $resep->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                @if($resep->status != 'pending')
                <div class="timeline-item {{ in_array($resep->status, ['diproses', 'selesai']) ? 'active' : 'rejected' }}">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <strong>{{ $resep->status == 'ditolak' ? 'Ditolak' : 'Validasi Apoteker' }}</strong>
                        <p class="text-muted small mb-0">{{ $resep->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                @endif

                @if($resep->status == 'selesai')
                <div class="timeline-item active">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <strong>Resep Selesai</strong>
                        <p class="text-muted small mb-0">{{ $resep->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        @if($resep->status == 'pending')
        <div class="card-dashboard mt-3">
            <div class="alert alert-warning mb-0">
                <i class="fas fa-clock"></i> <strong>Menunggu Validasi</strong><br>
                <small>Resep Anda sedang menunggu validasi dari apoteker kami. Harap bersabar.</small>
            </div>
        </div>
        @elseif($resep->status == 'diproses')
        <div class="card-dashboard mt-3">
            <div class="alert alert-info mb-0">
                <i class="fas fa-spinner"></i> <strong>Sedang Diproses</strong><br>
                <small>Resep Anda sedang diproses oleh apoteker kami.</small>
            </div>
        </div>
        @elseif($resep->status == 'selesai')
        <div class="card-dashboard mt-3">
            <div class="alert alert-success mb-0">
                <i class="fas fa-check-circle"></i> <strong>Resep Selesai</strong><br>
                <small>Resep telah divalidasi dan obat siap diambil/dikirim.</small>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    padding-bottom: 25px;
}
.timeline-item:not(:last-child):before {
    content: '';
    position: absolute;
    left: 7px;
    top: 25px;
    height: calc(100% - 15px);
    width: 2px;
    background: #ddd;
}
.timeline-item.active:not(:last-child):before {
    background: #0d6efd;
}
.timeline-item.rejected:not(:last-child):before {
    background: #dc3545;
}
.timeline-marker {
    position: absolute;
    left: 0;
    top: 3px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #ddd;
    border: 3px solid #fff;
}
.timeline-item.active .timeline-marker {
    background: #0d6efd;
}
.timeline-item.rejected .timeline-marker {
    background: #dc3545;
}
.timeline-content {
    margin-left: 25px;
}
</style>
@endsection
