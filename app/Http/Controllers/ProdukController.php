<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Obat::with(['kategori'])->available();

        // Filter berdasarkan kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('id_kategori', $request->kategori);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nama_obat', 'like', '%' . $request->search . '%')
                  ->orWhere('jenis_obat', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi_obat', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'harga_asc':
                    $query->orderBy('harga_obat', 'asc');
                    break;
                case 'harga_desc':
                    $query->orderBy('harga_obat', 'desc');
                    break;
                case 'nama_asc':
                    $query->orderBy('nama_obat', 'asc');
                    break;
                case 'nama_desc':
                    $query->orderBy('nama_obat', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $obats = $query->paginate(12);
        $kategoris = Kategori::all();

        return view('pelanggan.produk.index', compact('obats', 'kategoris'));
    }

    public function show(Obat $obat)
    {
        $obat->load(['kategori', 'supplier']);
        $relatedObats = Obat::where('id_kategori', $obat->id_kategori)
            ->where('id_obat', '!=', $obat->id_obat)
            ->available()
            ->limit(4)
            ->get();

        return view('pelanggan.produk.show', compact('obat', 'relatedObats'));
    }
}
