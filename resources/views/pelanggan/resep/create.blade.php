@extends('layouts.dashboard')
@section('title', 'Upload Resep Dokter')

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
                <h2 class="fw-bold mb-1">Upload Resep Dokter</h2>
                <p class="text-muted">Upload resep dari dokter untuk divalidasi apoteker</p>
            </div>
            <a href="{{ route('pelanggan.resep.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card-dashboard">
            <form action="{{ route('pelanggan.resep.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> <strong>Perhatian:</strong> 
                    Pastikan foto resep jelas dan dapat terbaca. Resep akan divalidasi oleh apoteker kami.
                </div>

                <div class="mb-3">
                    <label for="nama_pasien" class="form-label">Nama Pasien <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_pasien') is-invalid @enderror" id="nama_pasien" name="nama_pasien" value="{{ old('nama_pasien') }}" required>
                    @error('nama_pasien')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nama_dokter" class="form-label">Nama Dokter <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_dokter') is-invalid @enderror" id="nama_dokter" name="nama_dokter" value="{{ old('nama_dokter') }}" placeholder="Contoh: dr. Ahmad Hidayat, Sp.PD" required>
                    @error('nama_dokter')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="diagnosa" class="form-label">Diagnosa <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('diagnosa') is-invalid @enderror" id="diagnosa" name="diagnosa" rows="3" placeholder="Contoh: Hipertensi Grade 2" required>{{ old('diagnosa') }}</textarea>
                    @error('diagnosa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tanggal_resep" class="form-label">Tanggal Resep <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal_resep') is-invalid @enderror" id="tanggal_resep" name="tanggal_resep" value="{{ old('tanggal_resep', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                    @error('tanggal_resep')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="foto_resep" class="form-label">Foto Resep <span class="text-danger">*</span></label>
                    <input type="file" class="form-control @error('foto_resep') is-invalid @enderror" id="foto_resep" name="foto_resep" accept="image/*" onchange="previewImage(event)" required>
                    @error('foto_resep')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Format: JPG, PNG. Max: 5MB</small>
                    
                    <div id="imagePreview" class="mt-3" style="display: none;">
                        <img id="preview" src="" alt="Preview" class="img-fluid rounded border">
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload Resep
                    </button>
                    <a href="{{ route('pelanggan.resep.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-dashboard">
            <h6 class="fw-bold mb-3"><i class="fas fa-lightbulb text-warning"></i> Tips Upload Resep</h6>
            <ul class="small">
                <li class="mb-2">Pastikan foto jelas dan tidak blur</li>
                <li class="mb-2">Tulisan resep harus terbaca</li>
                <li class="mb-2">Terdapat stempel dan tanda tangan dokter</li>
                <li class="mb-2">Foto dalam pencahayaan yang cukup</li>
                <li class="mb-2">Sertakan informasi lengkap</li>
            </ul>
        </div>

        <div class="card-dashboard mt-3">
            <h6 class="fw-bold mb-3"><i class="fas fa-clock text-info"></i> Proses Validasi</h6>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-marker bg-primary"></div>
                    <div class="timeline-content">
                        <small class="text-muted">Step 1</small>
                        <p class="mb-0 small">Upload resep dokter</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker bg-warning"></div>
                    <div class="timeline-content">
                        <small class="text-muted">Step 2</small>
                        <p class="mb-0 small">Validasi oleh apoteker</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker bg-success"></div>
                    <div class="timeline-content">
                        <small class="text-muted">Step 3</small>
                        <p class="mb-0 small">Resep diproses</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const previewDiv = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewDiv.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    padding-bottom: 20px;
}
.timeline-item:not(:last-child):before {
    content: '';
    position: absolute;
    left: 6px;
    top: 20px;
    height: calc(100% - 10px);
    width: 2px;
    background: #ddd;
}
.timeline-marker {
    position: absolute;
    left: 0;
    top: 3px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}
.timeline-content {
    margin-left: 25px;
}
</style>
@endsection
