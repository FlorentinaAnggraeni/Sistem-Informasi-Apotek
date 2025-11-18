<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::latest()->get();
        return view('penjualan.kelola_penjualan', compact('penjualans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'nama_obat' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'total_harga' => 'required|numeric|min:0',
        ]);

        Penjualan::create([
            'kode_pesanan' => 'PSN-' . strtoupper(uniqid()),
            'nama_pelanggan' => $validated['nama_pelanggan'],
            'nama_obat' => $validated['nama_obat'],
            'jumlah' => $validated['jumlah'],
            'total_harga' => $validated['total_harga'],
        ]);

        return redirect()->back()->with('success', 'Pesanan berhasil diterima!');
    }

    public function konfirmasi($id)
    {
        $pesanan = Penjualan::findOrFail($id);
        $pesanan->update(['status' => 'Dikonfirmasi']);

        return redirect()->back()->with('success', 'Pesanan berhasil dikonfirmasi!');
    }

    public function destroy($id)
    {
        $pesanan = Penjualan::findOrFail($id);
        $pesanan->delete();

        return redirect()->back()->with('success', 'Pesanan berhasil dihapus!');
    }
}
