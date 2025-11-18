<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obat;
use App\Models\Transaksi;
use App\Models\Pesanan;

class KaryawanController extends Controller
{
    public function kelolaObat()
    {
        $obats = Obat::all();
        return view('dashboard.kelolaobat', compact('obats'));

    }

    public function storeObat(Request $request)
    {
        Obat::create($request->all());
        return redirect()->route('apoteker.karyawan.kelola-obat')->with('success', 'Obat berhasil ditambahkan');
    }

    public function editObat($id)
    {
        $obat = Obat::findOrFail($id);
        return view('edit_obat', compact('obat'));
    }

    public function updateObat(Request $request, $id)
    {
        $obat = Obat::findOrFail($id);
        $obat->update($request->all());
        return redirect()->route('apoteker.karyawan.kelola-obat')->with('success', 'Obat berhasil diupdate');
    }

    public function destroyObat($id)
    {
        $obat = Obat::findOrFail($id);
        $obat->delete();
        return redirect()->route('apoteker.karyawan.kelola-obat')->with('success', 'Obat berhasil dihapus');
    }

    public function transaksi()
    {
        $transaksis = Transaksi::all();
        return view('transaksi', compact('transaksis'));
    }

    public function pesananMasuk()
    {
        $pesanans = Pesanan::where('status', 'baru')->get();
        return view('pesanan_masuk', compact('pesanans'));
    }
}
