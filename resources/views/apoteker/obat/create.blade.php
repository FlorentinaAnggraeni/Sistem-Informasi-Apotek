@extends('layouts.dashboard')
@section('title', 'Tambah Obat')

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
                <h2 class="fw-bold mb-1">Tambah Obat Baru</h2>
                <p class="text-muted">Lengkapi form untuk menambah obat</p>
            </div>
            <a href="{{ route('apoteker.obat.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<form action="{{ route('apoteker.obat.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card-dashboard">
                <h5 class="fw-bold mb-4">Informasi Obat</h5>
                
                <div class="mb-3">
                    <label for="nama_obat" class="form-label">Nama Obat <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_obat') is-invalid @enderror" id="nama_obat" name="nama_obat" value="{{ old('nama_obat') }}" required>
                    @error('nama_obat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="jenis_obat" class="form-label">Jenis Obat <span class="text-danger">*</span></label>
                        <select class="form-select @error('jenis_obat') is-invalid @enderror" id="jenis_obat" name="jenis_obat" required>
                            <option value="">Pilih Jenis</option>
                            <option value="Tablet" {{ old('jenis_obat') == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                            <option value="Kapsul" {{ old('jenis_obat') == 'Kapsul' ? 'selected' : '' }}>Kapsul</option>
                            <option value="Sirup" {{ old('jenis_obat') == 'Sirup' ? 'selected' : '' }}>Sirup</option>
                            <option value="Salep" {{ old('jenis_obat') == 'Salep' ? 'selected' : '' }}>Salep</option>
                            <option value="Injeksi" {{ old('jenis_obat') == 'Injeksi' ? 'selected' : '' }}>Injeksi</option>
                            <option value="Tetes" {{ old('jenis_obat') == 'Tetes' ? 'selected' : '' }}>Tetes</option>
                            <option value="Alat Kesehatan" {{ old('jenis_obat') == 'Alat Kesehatan' ? 'selected' : '' }}>Alat Kesehatan</option>
                        </select>
                        @error('jenis_obat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
                        <select class="form-select @error('satuan') is-invalid @enderror" id="satuan" name="satuan" required>
                            <option value="">Pilih Satuan</option>
                            <option value="Strip" {{ old('satuan') == 'Strip' ? 'selected' : '' }}>Strip</option>
                            <option value="Box" {{ old('satuan') == 'Box' ? 'selected' : '' }}>Box</option>
                            <option value="Botol" {{ old('satuan') == 'Botol' ? 'selected' : '' }}>Botol</option>
                            <option value="Tube" {{ old('satuan') == 'Tube' ? 'selected' : '' }}>Tube</option>
                            <option value="Pcs" {{ old('satuan') == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                        </select>
                        @error('satuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="deskripsi_obat" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi_obat') is-invalid @enderror" id="deskripsi_obat" name="deskripsi_obat" rows="3">{{ old('deskripsi_obat') }}</textarea>
                    @error('deskripsi_obat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="harga_obat" class="form-label">Harga <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('harga_obat') is-invalid @enderror" id="harga_obat" name="harga_obat" value="{{ old('harga_obat') }}" min="0" required>
                        </div>
                        @error('harga_obat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="stok_obat" class="form-label">Stok <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('stok_obat') is-invalid @enderror" id="stok_obat" name="stok_obat" value="{{ old('stok_obat', 0) }}" min="0" required>
                        @error('stok_obat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="tanggal_kadaluarsa" class="form-label">Tanggal Kadaluarsa</label>
                        <input type="date" class="form-control @error('tanggal_kadaluarsa') is-invalid @enderror" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" value="{{ old('tanggal_kadaluarsa') }}">
                        @error('tanggal_kadaluarsa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="id_kategori" class="form-label">Kategori</label>
                        <select class="form-select @error('id_kategori') is-invalid @enderror" id="id_kategori" name="id_kategori">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id_kategori }}" {{ old('id_kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                        @error('id_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="id_supplier" class="form-label">Supplier</label>
                        <select class="form-select @error('id_supplier') is-invalid @enderror" id="id_supplier" name="id_supplier">
                            <option value="">Pilih Supplier</option>
                            @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id_supplier }}" {{ old('id_supplier') == $supplier->id_supplier ? 'selected' : '' }}>
                                {{ $supplier->nama_supplier }}
                            </option>
                            @endforeach
                        </select>
                        @error('id_supplier')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="no_batch" class="form-label">No. Batch</label>
                    <input type="text" class="form-control @error('no_batch') is-invalid @enderror" id="no_batch" name="no_batch" value="{{ old('no_batch') }}">
                    @error('no_batch')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-dashboard">
                <h5 class="fw-bold mb-4">Gambar Obat</h5>
                
                <div class="mb-3">
                    <label for="gambar_obat" class="form-label">Upload Gambar</label>
                    <input type="file" class="form-control @error('gambar_obat') is-invalid @enderror" id="gambar_obat" name="gambar_obat" accept="image/*" onchange="previewImage(event)">
                    @error('gambar_obat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Format: JPG, PNG. Max: 2MB</small>
                </div>

                <div id="imagePreview" class="mt-3" style="display: none;">
                    <img id="preview" src="" alt="Preview" class="img-fluid rounded">
                </div>
            </div>

            <div class="card-dashboard mt-3">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('apoteker.obat.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

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
@endsection
