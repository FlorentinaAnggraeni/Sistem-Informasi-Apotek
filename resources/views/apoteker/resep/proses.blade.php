@extends('layouts.dashboard')
@section('title', 'Proses Resep')

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
                <h2 class="fw-bold mb-1">Proses Resep</h2>
                <p class="text-muted">{{ $resep->no_resep }}</p>
            </div>
            <a href="{{ route('apoteker.resep.show', $resep->id_resep) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Info Resep -->
    <div class="col-md-8">
        <div class="card-dashboard mb-4">
            <h5 class="fw-bold mb-3">Informasi Resep</h5>
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-1">No. Resep</p>
                    <p class="fw-bold">{{ $resep->no_resep }}</p>
                    
                    <p class="text-muted mb-1 mt-3">Nama Pasien</p>
                    <p class="fw-bold">{{ $resep->nama_pasien }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1">Tanggal</p>
                    <p>{{ $resep->tanggal_resep->format('d F Y') }}</p>
                    
                    <p class="text-muted mb-1 mt-3">Nama Dokter</p>
                    <p>{{ $resep->nama_dokter }}</p>
                </div>
            </div>
            
            <p class="text-muted mb-1">Diagnosa</p>
            <p>{{ $resep->diagnosa }}</p>

            @if($resep->foto_resep)
            <hr>
            <p class="text-muted mb-2">Foto Resep</p>
            <img src="{{ asset('storage/' . $resep->foto_resep) }}" alt="Foto Resep" class="img-fluid rounded" style="max-height: 300px">
            @endif
        </div>

        <!-- Form Proses -->
        <div class="card-dashboard">
            <h5 class="fw-bold mb-4">Form Validasi Resep</h5>
            
            <form action="{{ route('apoteker.resep.updateStatus', $resep->id_resep) }}" method="POST" id="formProsesResep">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="form-label fw-bold">Status Resep <span class="text-danger">*</span></label>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check card-dashboard p-3">
                                <input class="form-check-input" type="radio" name="status" id="statusProses" value="diproses" required>
                                <label class="form-check-label" for="statusProses">
                                    <i class="fas fa-spinner text-info"></i> <strong>Diproses</strong>
                                    <p class="text-muted small mb-0">Resep valid, lanjut proses</p>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check card-dashboard p-3">
                                <input class="form-check-input" type="radio" name="status" id="statusSelesai" value="selesai">
                                <label class="form-check-label" for="statusSelesai">
                                    <i class="fas fa-check-circle text-success"></i> <strong>Selesai</strong>
                                    <p class="text-muted small mb-0">Resep sudah dipenuhi</p>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check card-dashboard p-3">
                                <input class="form-check-input" type="radio" name="status" id="statusTolak" value="ditolak">
                                <label class="form-check-label" for="statusTolak">
                                    <i class="fas fa-times-circle text-danger"></i> <strong>Ditolak</strong>
                                    <p class="text-muted small mb-0">Resep tidak valid</p>
                                </label>
                            </div>
                        </div>
                    </div>
                    @error('status')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="catatan_apoteker" class="form-label fw-bold">Catatan Apoteker</label>
                    <textarea class="form-control" id="catatan_apoteker" name="catatan_apoteker" rows="4" placeholder="Tambahkan catatan atau alasan penolakan...">{{ old('catatan_apoteker') }}</textarea>
                    <small class="text-muted">Wajib diisi jika status ditolak</small>
                    @error('catatan_apoteker')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div id="detailObatSection" style="display: none;">
                    <hr>
                    <h6 class="fw-bold mb-3">Detail Obat yang Diresepkan</h6>
                    <p class="text-muted small">Tambahkan obat-obatan yang diperlukan sesuai resep dokter</p>
                    
                    <div id="obatContainer">
                        <div class="obat-item card-dashboard p-3 mb-3">
                            <div class="row align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label">Obat <span class="text-danger">*</span></label>
                                    <select class="form-select" name="obat[0][id_obat]">
                                        <option value="">Pilih Obat</option>
                                        @foreach($obats as $obat)
                                        <option value="{{ $obat->id_obat }}" data-stok="{{ $obat->stok }}">
                                            {{ $obat->nama_obat }} (Stok: {{ $obat->stok }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="obat[0][jumlah]" min="1" placeholder="0">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Aturan Pakai <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="obat[0][aturan_pakai]" placeholder="3x sehari sesudah makan">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm remove-obat" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <label class="form-label">Catatan (Opsional)</label>
                                    <input type="text" class="form-control" name="obat[0][catatan]" placeholder="Catatan tambahan">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm" id="addObat">
                        <i class="fas fa-plus"></i> Tambah Obat
                    </button>
                </div>

                <hr>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('apoteker.resep.show', $resep->id_resep) }}" class="btn btn-secondary">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Pelanggan -->
    <div class="col-md-4">
        <div class="card-dashboard">
            <h6 class="fw-bold mb-3">Informasi Pelanggan</h6>
            <p class="mb-2"><strong>{{ $resep->pelanggan->nama_pelanggan }}</strong></p>
            <p class="text-muted small mb-1"><i class="fas fa-envelope"></i> {{ $resep->pelanggan->email_pelanggan }}</p>
            <p class="text-muted small mb-1"><i class="fas fa-phone"></i> {{ $resep->pelanggan->no_telp_pelanggan }}</p>
            <p class="text-muted small mb-0"><i class="fas fa-map-marker-alt"></i> {{ $resep->pelanggan->alamat_pelanggan }}</p>
        </div>

        <div class="card-dashboard mt-3">
            <h6 class="fw-bold mb-3">Panduan</h6>
            <ul class="small text-muted ps-3">
                <li>Periksa foto resep dengan teliti</li>
                <li>Validasi tanda tangan dan stempel dokter</li>
                <li>Pastikan obat tersedia dan stok mencukupi</li>
                <li>Tambahkan catatan jika ada instruksi khusus</li>
                <li>Tolak resep jika tidak valid atau mencurigakan</li>
            </ul>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const detailObatSection = document.getElementById('detailObatSection');
    const statusRadios = document.querySelectorAll('input[name="status"]');
    const obatContainer = document.getElementById('obatContainer');
    const addObatBtn = document.getElementById('addObat');
    let obatIndex = 1;

    // Show/hide detail obat based on status
    statusRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'diproses' || this.value === 'selesai') {
                detailObatSection.style.display = 'block';
            } else {
                detailObatSection.style.display = 'none';
            }
        });
    });

    // Add obat item
    addObatBtn.addEventListener('click', function() {
        const obatItem = document.querySelector('.obat-item').cloneNode(true);
        
        // Update name attributes
        obatItem.querySelectorAll('[name^="obat[0]"]').forEach(input => {
            const name = input.getAttribute('name').replace('[0]', `[${obatIndex}]`);
            input.setAttribute('name', name);
            input.value = '';
        });

        // Enable remove button
        const removeBtn = obatItem.querySelector('.remove-obat');
        removeBtn.disabled = false;
        removeBtn.addEventListener('click', function() {
            obatItem.remove();
        });

        obatContainer.appendChild(obatItem);
        obatIndex++;
    });

    // Remove obat item (for dynamically added items)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-obat') && !e.target.closest('.remove-obat').disabled) {
            e.target.closest('.obat-item').remove();
        }
    });

    // Form validation
    document.getElementById('formProsesResep').addEventListener('submit', function(e) {
        const status = document.querySelector('input[name="status"]:checked');
        const catatan = document.getElementById('catatan_apoteker').value;

        if (!status) {
            e.preventDefault();
            alert('Pilih status resep terlebih dahulu');
            return;
        }

        if (status.value === 'ditolak' && !catatan.trim()) {
            e.preventDefault();
            alert('Catatan apoteker wajib diisi jika menolak resep');
            document.getElementById('catatan_apoteker').focus();
            return;
        }

        if ((status.value === 'diproses' || status.value === 'selesai')) {
            const obatSelect = document.querySelector('select[name="obat[0][id_obat]"]');
            const jumlah = document.querySelector('input[name="obat[0][jumlah]"]');
            const aturanPakai = document.querySelector('input[name="obat[0][aturan_pakai]"]');

            if (!obatSelect.value || !jumlah.value || !aturanPakai.value) {
                e.preventDefault();
                alert('Lengkapi minimal 1 obat untuk diproses');
                return;
            }
        }
    });
});
</script>

<style>
.form-check-input:checked ~ .form-check-label {
    color: #0d6efd;
}
.obat-item {
    border-left: 3px solid #0d6efd;
}
</style>
@endsection
