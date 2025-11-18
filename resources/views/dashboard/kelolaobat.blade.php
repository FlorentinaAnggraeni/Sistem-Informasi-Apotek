@extends('layouts.app')

@section('title', 'Kelola Obat')

@section('content')
<div class="container-fluid d-flex flex-column justify-content-between"
     style="background-color: #e8ffff; min-height: 100vh; border: 10px solid #00baba; position: relative; overflow-x: hidden;">

    {{-- Tombol Back --}}
    <div class="position-absolute top-0 start-0 m-3" style="z-index: 999;">
        <a href="{{ route('dashboard') }}" class="text-dark" title="Kembali">
            <i class="fas fa-arrow-left fa-2x"></i>
        </a>
    </div>

    {{-- MENU UTAMA --}}
    <div id="mainMenu" class="d-flex justify-content-center align-items-center flex-wrap gap-5 my-auto">

        <button type="button" id="btnStok" class="btn btn-link p-0 text-decoration-none">
            <div class="menu-card shadow-lg p-4 rounded-4 bg-white text-center" style="position: relative; z-index: 10;">
                <img src="{{ asset('images/LihatStok-Obat.png') }}" width="140" class="mb-2">
                <div class="fw-bold fs-5" style="color: #0077b6;">Lihat Stok</div>
            </div>
        </button>

        <button type="button" id="btnTambah" class="btn btn-link p-0 text-decoration-none">
            <div class="menu-card shadow-lg p-4 rounded-4 bg-white text-center" style="position: relative; z-index: 10;">
                <img src="{{ asset('images/TambahObat.png') }}" width="140" class="mb-2">
                <div class="fw-bold fs-5" style="color: #00c16a;">Tambah Obat</div>
            </div>
        </button>

        <button type="button" id="btnEdit" class="btn btn-link p-0 text-decoration-none">
            <div class="menu-card shadow-lg p-4 rounded-4 bg-white text-center" style="position: relative; z-index: 10;">
                <img src="{{ asset('images/EditObat.png') }}" width="140" class="mb-2">
                <div class="fw-bold fs-5" style="color: #009da0;">Edit Obat</div>
            </div>
        </button>

        <button type="button" id="btnHapus" class="btn btn-link p-0 text-decoration-none">
            <div class="menu-card shadow-lg p-4 rounded-4 bg-white text-center" style="position: relative; z-index: 10;">
                <img src="{{ asset('images/HapusObat.png') }}" width="140" class="mb-2">
                <div class="fw-bold fs-5" style="color: #ff5a5a;">Hapus Obat</div>
            </div>
        </button>

    </div>

    {{-- TAMBAH OBAT --}}
    <div id="tambahSection" class="d-none text-dark text-center my-auto fade-section">
        <h3 class="fw-bold mb-4 text-primary">Tambah Obat Baru</h3>

        <form action="{{ route('apoteker.karyawan.obat.store') }}" method="POST"
              class="mx-auto bg-white shadow-lg p-4 rounded-4" style="max-width: 450px;">
            @csrf
            <div class="mb-3"><input type="text" name="kode_obat" class="form-control" placeholder="Kode Obat"></div>
            <div class="mb-3"><input type="text" name="nama_obat" class="form-control" placeholder="Nama Obat"></div>
            <div class="mb-3"><input type="text" name="kategori" class="form-control" placeholder="Kategori"></div>
            <div class="mb-3"><input type="number" name="stok" class="form-control" placeholder="Stok"></div>
            <div class="mb-3"><input type="number" name="harga" class="form-control" placeholder="Harga"></div>
            <div class="mb-3"><input type="date" name="tanggal_expired" class="form-control"></div>

            <div class="d-flex justify-content-center gap-2">
                <button type="submit" class="btn btn-success fw-bold px-4">Simpan</button>
                <button type="button" class="btn btn-secondary px-4" id="btnKembaliFromTambah">Kembali</button>
            </div>
        </form>
    </div>

    {{-- EDIT OBAT --}}
    <div id="editSection" class="d-none text-dark my-auto text-center fade-section">
        <h3 class="fw-bold mb-4 text-info">Edit Data Obat</h3>

        <div class="table-responsive mx-auto bg-white shadow-lg p-4 rounded-4" style="max-width: 90%;">
            <table class="table table-hover align-middle">
                <thead class="table-info">
                    <tr>
                        <th>No</th><th>Nama Obat</th><th>Kategori</th><th>Harga</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($obats as $key => $obat)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $obat->nama_obat }}</td>
                        <td>{{ $obat->kategori }}</td>
                        <td>Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm btnEditObat"
                                data-id="{{ $obat->id }}"
                                data-nama="{{ $obat->nama_obat }}"
                                data-kategori="{{ $obat->kategori }}"
                                data-stok="{{ $obat->stok }}"
                                data-harga="{{ $obat->harga }}"
                                data-expired="{{ $obat->tanggal_expired }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button class="btn btn-secondary mt-3" id="btnKembaliFromEdit">Kembali</button>
    </div>

    {{-- HAPUS OBAT --}}
    <div id="hapusSection" class="d-none text-dark my-auto text-center fade-section">
        <h3 class="fw-bold mb-4 text-danger">Hapus Data Obat</h3>

        <div class="table-responsive mx-auto bg-white shadow-lg p-4 rounded-4" style="max-width: 90%;">
            <table class="table table-hover align-middle">
                <thead class="table-danger">
                    <tr>
                        <th>No</th><th>Nama Obat</th><th>Kategori</th><th>Harga</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($obats as $key => $obat)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $obat->nama_obat }}</td>
                        <td>{{ $obat->kategori }}</td>
                        <td>Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('apoteker.karyawan.obat.destroy', $obat->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus obat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button class="btn btn-secondary mt-3" id="btnKembaliFromHapus">Kembali</button>
    </div>

    {{-- LIHAT STOK --}}
    <div id="stokSection" class="d-none text-dark my-auto text-center fade-section">
        <h3 class="fw-bold mb-4 text-success">Lihat Stok Obat</h3>

        <div class="table-responsive mx-auto bg-white shadow-lg p-4 rounded-4" style="max-width: 90%;">
            <table class="table table-striped align-middle">
                <thead class="table-success">
                    <tr>
                        <th>No</th>
                        <th>Nama Obat</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Tanggal Kadaluarsa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($obats as $key => $obat)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $obat->nama_obat }}</td>
                        <td>{{ $obat->kategori }}</td>
                        <td>{{ $obat->stok }}</td>
                        <td>{{ $obat->tanggal_expired }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button class="btn btn-secondary mt-3" id="btnKembaliFromStok">Kembali</button>
    </div>

</div>
@endsection


@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    function showSection(sectionId) {
        const sections = ["mainMenu", "tambahSection", "editSection", "hapusSection", "stokSection"];
        sections.forEach(s => document.getElementById(s).classList.add("d-none"));
        document.getElementById(sectionId).classList.remove("d-none");
    }

    function bindClick(id, section) {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener("click", () => showSection(section));
        }
    }

    // Tombol menu utama
    bindClick("btnTambah", "tambahSection");
    bindClick("btnEdit", "editSection");
    bindClick("btnHapus", "hapusSection");
    bindClick("btnStok", "stokSection");

    // Tombol kembali
    bindClick("btnKembaliFromTambah", "mainMenu");
    bindClick("btnKembaliFromEdit", "mainMenu");
    bindClick("btnKembaliFromHapus", "mainMenu");
    bindClick("btnKembaliFromStok", "mainMenu");

});
</script>
@endsection