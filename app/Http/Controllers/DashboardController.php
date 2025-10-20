<?php

namespace App\Http\Controllers;

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
        $obat = \App\Models\Obat::all();
        return view('pelanggan.cek-stok', compact('obat'));
    }
public function pesanObat()
{
    $obat = \App\Models\Obat::all();
    return view('pelanggan.pesan-obat', compact('obat'));
}

public function riwayat()
{
    // $pesanan = Pesanan::where('user_id', auth()->id())->get();
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