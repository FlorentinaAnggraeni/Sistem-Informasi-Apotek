@extends('layouts.dashboard')
@section('title', 'Tambah Karyawan')

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
                <h2 class="fw-bold mb-1">Tambah Karyawan Baru</h2>
                <p class="text-muted">Lengkapi formulir di bawah untuk menambahkan karyawan</p>
            </div>
            <a href="{{ route('pemilik.karyawan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card-dashboard">
            <form action="{{ route('pemilik.karyawan.store') }}" method="POST">
                @csrf
                
                <h5 class="fw-bold mb-3"><i class="fas fa-user-circle text-primary"></i> Informasi Akun</h5>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" 
                               name="username" value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <select class="form-select @error('role') is-invalid @enderror" name="role" required>
                        <option value="">Pilih Role</option>
                        <option value="karyawan" {{ old('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                        <option value="apoteker" {{ old('role') == 'apoteker' ? 'selected' : '' }}>Apoteker</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">
                
                <h5 class="fw-bold mb-3"><i class="fas fa-briefcase text-success"></i> Informasi Karyawan</h5>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Karyawan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_karyawan') is-invalid @enderror" 
                               name="nama_karyawan" value="{{ old('nama_karyawan') }}" required>
                        @error('nama_karyawan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('jabatan') is-invalid @enderror" 
                               name="jabatan" value="{{ old('jabatan') }}" placeholder="Contoh: Staff Gudang" required>
                        @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" class="form-control @error('no_telp_karyawan') is-invalid @enderror" 
                               name="no_telp_karyawan" value="{{ old('no_telp_karyawan') }}" 
                               placeholder="08xxxxxxxxxx">
                        @error('no_telp_karyawan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Bergabung</label>
                        <input type="date" class="form-control @error('tanggal_bergabung') is-invalid @enderror" 
                               name="tanggal_bergabung" value="{{ old('tanggal_bergabung', date('Y-m-d')) }}">
                        @error('tanggal_bergabung')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Gaji</label>
                        <input type="number" class="form-control @error('gaji') is-invalid @enderror" 
                               name="gaji" value="{{ old('gaji') }}" placeholder="0" min="0">
                        @error('gaji')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea class="form-control @error('alamat_karyawan') is-invalid @enderror" 
                              name="alamat_karyawan" rows="3" placeholder="Alamat lengkap karyawan">{{ old('alamat_karyawan') }}</textarea>
                    @error('alamat_karyawan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Karyawan
                    </button>
                    <a href="{{ route('pemilik.karyawan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-dashboard bg-light">
            <h6 class="fw-bold mb-3"><i class="fas fa-info-circle text-info"></i> Informasi</h6>
            <ul class="small text-muted mb-0" style="list-style: none; padding-left: 0;">
                <li class="mb-2"><i class="fas fa-check-circle text-success"></i> Pastikan email belum terdaftar</li>
                <li class="mb-2"><i class="fas fa-check-circle text-success"></i> Password minimal 8 karakter</li>
                <li class="mb-2"><i class="fas fa-check-circle text-success"></i> Apoteker memiliki akses lebih</li>
                <li class="mb-2"><i class="fas fa-check-circle text-success"></i> Data dapat diubah setelah disimpan</li>
            </ul>
        </div>
    </div>
</div>
@endsection
