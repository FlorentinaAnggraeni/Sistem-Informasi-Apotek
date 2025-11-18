<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Dashboard utama (redirect ke dashboard sesuai role)
    public function index()
    {
        $role = Auth::user()->role;

        switch ($role) {
            case 'pelanggan':
                return view('dashboard.pelanggan');
            case 'karyawan':
            case 'apoteker':
                return view('dashboard.apoteker'); // gunakan 1 view gabungan
            case 'pemilik':
                return view('dashboard.pemilik');
            default:
                abort(403);
        }
    }

    // === PELANGGAN ROUTES ===
    public function produkPelanggan()
    {
        return view('pelanggan.produk');
    }

    public function cekStok()
    {
        $obat = Obat::all();
        return view('pelanggan.cek-stok', compact('obat'));
    }

    public function pesanObat()
    {
        $obat = Obat::all();
        return view('pelanggan.pesan-obat', compact('obat'));
    }

    public function riwayat()
    {
        return view('pelanggan.riwayat');
    }

    public function pesanan()
    {
        return view('pelanggan.pesanan');
    }

    public function profil()
    {
        return view('pelanggan.profil');
    }

    // === PEMILIK ROUTES ===
    public function stokObat()
    {
        $obat = Obat::all();
        return view('pemilik.stok-obat', compact('obat'));
    }

    public function laporan()
    {
        return view('pemilik.laporan');
    }

    public function kelolaUser()
    {
        return view('pemilik.kelola-user');
    }

    public function pengaturan()
    {
        return view('pemilik.pengaturan');
    }

    // === KARYAWAN / APOTEKER ROUTES ===
    public function karyawanKelolaObat()
    {
        $obats = Obat::paginate(10); // ambil data dengan pagination
        return view('dashboard.kelolaobat', compact('obats')); // pastikan file view ini ADA
    }

    public function transaksi()
    {
        return view('karyawan.transaksi');
    }

    public function pesananMasuk()
    {
        return view('karyawan.pesanan-masuk');
    }
}
