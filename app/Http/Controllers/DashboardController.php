<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Dashboard utama (redirect ke dashboard sesuai role)
    public function index()
    {
        $user = auth()->user();
        
        switch ($user->role) {
            case 'pemilik':
                return view('dashboard.pemilik');
            case 'apoteker':
                return view('dashboard.apoteker');
            case 'karyawan':
                return view('dashboard.karyawan');
            case 'pelanggan':
            default:
                return view('dashboard.pelanggan');
        }
    }

    // === PELANGGAN ROUTES ===
    public function produkPelanggan()
    {
        return view('pelanggan.produk');
    }

    public function cekStok()
    {
        $obat = \App\Models\Obat::all();
        return view('pelanggan.cek-stok', compact('obat'));
    }

    public function keranjang()
    {
        return view('pelanggan.keranjang');
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
        $obat = \App\Models\Obat::all();
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

    // === APOTEKER ROUTES ===
    public function apotekerKelolaObat()
    {
        $obat = \App\Models\Obat::all();
        return view('apoteker.kelola-obat', compact('obat'));
    }

    public function stok()
    {
        return view('apoteker.stok');
    }

    public function resep()
    {
        return view('apoteker.resep');
    }

    // === KARYAWAN ROUTES ===
    public function karyawanKelolaObat()
    {
        $obat = \App\Models\Obat::all();
        return view('karyawan.kelola-obat', compact('obat'));
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