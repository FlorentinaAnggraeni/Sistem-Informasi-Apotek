<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Obat;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::where('id_user', Auth::id())->first();
        
        if (!$pelanggan) {
            return redirect()->route('dashboard')->with('error', 'Data pelanggan tidak ditemukan');
        }

        $keranjangs = Keranjang::where('id_pelanggan', $pelanggan->id_pelanggan)
            ->with('obat')
            ->get();

        $total = $keranjangs->sum(function($item) {
            return $item->jumlah * $item->obat->harga_obat;
        });

        return view('pelanggan.keranjang.index', compact('keranjangs', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_obat' => 'required|exists:obats,id_obat',
            'jumlah' => 'required|integer|min:1',
        ]);

        $pelanggan = Pelanggan::where('id_user', Auth::id())->first();
        
        if (!$pelanggan) {
            return back()->with('error', 'Data pelanggan tidak ditemukan');
        }

        $obat = Obat::findOrFail($validated['id_obat']);

        // Cek stok
        if (!$obat->hasStock($validated['jumlah'])) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        // Cek apakah item sudah ada di keranjang
        $keranjang = Keranjang::where('id_pelanggan', $pelanggan->id_pelanggan)
            ->where('id_obat', $validated['id_obat'])
            ->first();

        if ($keranjang) {
            // Update jumlah
            $newJumlah = $keranjang->jumlah + $validated['jumlah'];
            
            if (!$obat->hasStock($newJumlah)) {
                return back()->with('error', 'Stok tidak mencukupi');
            }
            
            $keranjang->jumlah = $newJumlah;
            $keranjang->save();
        } else {
            // Buat item baru
            Keranjang::create([
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'id_obat' => $validated['id_obat'],
                'jumlah' => $validated['jumlah'],
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function update(Request $request, Keranjang $keranjang)
    {
        $validated = $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        // Cek stok
        if (!$keranjang->obat->hasStock($validated['jumlah'])) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        $keranjang->update($validated);

        return back()->with('success', 'Jumlah berhasil diupdate');
    }

    public function destroy(Keranjang $keranjang)
    {
        $keranjang->delete();

        return back()->with('success', 'Item berhasil dihapus dari keranjang');
    }

    public function clear()
    {
        $pelanggan = Pelanggan::where('id_user', Auth::id())->first();
        
        if ($pelanggan) {
            Keranjang::where('id_pelanggan', $pelanggan->id_pelanggan)->delete();
        }

        return back()->with('success', 'Keranjang berhasil dikosongkan');
    }
}
