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
                <h2 class="fw-bold mb-1">Detail Resep</h2>
                <p class="text-muted">{{ $resep->no_resep }}</p>
            </div>
            <a href="{{ route('apoteker.resep.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Info Resep -->
    <div class="col-md-8 mb-4">
        <div class="card-dashboard">
            <h5 class="fw-bold mb-3">Informasi Resep</h5>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted" width="150">No. Resep</td>
                            <td><strong>{{ $resep->no_resep }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal</td>
                            <td>{{ $resep->tanggal_resep->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
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
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted" width="150">Nama Pasien</td>
                            <td><strong>{{ $resep->nama_pasien }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nama Dokter</td>
                            <td>{{ $resep->nama_dokter }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Diagnosa</td>
                            <td>{{ $resep->diagnosa }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr>

            <h6 class="fw-bold mb-3">Informasi Pelanggan</h6>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted" width="150">Nama</td>
                            <td>{{ $resep->pelanggan->nama_pelanggan }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email</td>
                            <td>{{ $resep->pelanggan->email_pelanggan }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted" width="150">No. HP</td>
                            <td>{{ $resep->pelanggan->no_telp_pelanggan }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Alamat</td>
                            <td>{{ $resep->pelanggan->alamat_pelanggan }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($resep->foto_resep)
            <hr>
            <h6 class="fw-bold mb-3">Foto Resep</h6>
            <img src="{{ asset('storage/' . $resep->foto_resep) }}" alt="Foto Resep" class="img-fluid rounded" style="max-height: 400px">
            @endif

            @if($resep->catatan_apoteker)
            <hr>
            <h6 class="fw-bold mb-3">Catatan Apoteker</h6>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> {{ $resep->catatan_apoteker }}
            </div>
            @endif

            @if($resep->apoteker)
            <hr>
            <h6 class="fw-bold mb-3">Diproses Oleh</h6>
            <p class="mb-0"><strong>{{ $resep->apoteker->nama_karyawan }}</strong></p>
            <small class="text-muted">{{ $resep->apoteker->jabatan }}</small>
            @endif
        </div>

        <!-- Detail Obat -->
        @if($resep->detailReseps->count() > 0)
        <div class="card-dashboard mt-4">
            <h5 class="fw-bold mb-3">Obat yang Diresepkan</h5>
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
                            <td>{{ $detail->aturan_pakai }}</td>
                            <td>{{ $detail->catatan ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Aksi -->
    <div class="col-md-4">
        <div class="card-dashboard">
            <h5 class="fw-bold mb-3">Aksi</h5>
            
            @if($resep->status == 'pending')
            <a href="{{ route('apoteker.resep.proses', $resep->id_resep) }}" class="btn btn-primary w-100 mb-2">
                <i class="fas fa-check"></i> Proses Resep
            </a>
            @endif

            @if($resep->status == 'diproses')
            <div class="alert alert-info">
                <i class="fas fa-spinner"></i> Resep sedang diproses
            </div>
            @endif

            @if($resep->status == 'selesai')
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> Resep telah selesai
            </div>
            @endif

            @if($resep->status == 'ditolak')
            <div class="alert alert-danger">
                <i class="fas fa-times-circle"></i> Resep ditolak
            </div>
            @endif
        </div>

        <!-- Timeline Status -->
        <div class="card-dashboard mt-3">
            <h6 class="fw-bold mb-3">Timeline</h6>
            <div class="timeline">
                <div class="timeline-item">
                    <i class="fas fa-circle text-success"></i>
                    <div>
                        <strong>Resep Dibuat</strong>
                        <p class="text-muted small mb-0">{{ $resep->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                
                @if($resep->status != 'pending')
                <div class="timeline-item">
                    <i class="fas fa-circle {{ $resep->status == 'ditolak' ? 'text-danger' : 'text-info' }}"></i>
                    <div>
                        <strong>{{ $resep->status == 'ditolak' ? 'Ditolak' : 'Diproses' }}</strong>
                        <p class="text-muted small mb-0">{{ $resep->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                @endif

                @if($resep->status == 'selesai')
                <div class="timeline-item">
                    <i class="fas fa-check-circle text-success"></i>
                    <div>
                        <strong>Selesai</strong>
                        <p class="text-muted small mb-0">{{ $resep->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    padding-bottom: 20px;
    display: flex;
    gap: 15px;
}
.timeline-item:last-child {
    padding-bottom: 0;
}
.timeline-item:not(:last-child):before {
    content: '';
    position: absolute;
    left: 5px;
    top: 20px;
    height: calc(100% - 10px);
    width: 2px;
    background: #ddd;
}
.timeline-item i {
    font-size: 12px;
    position: relative;
    z-index: 1;
    background: white;
}
</style>
@endsection
