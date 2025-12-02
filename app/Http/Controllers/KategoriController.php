<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::withCount('obats')->latest()->paginate(10);
        return view('apoteker.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('apoteker.kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi_kategori' => 'nullable|string',
        ]);

        Kategori::create($validated);

        return redirect()->route('apoteker.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit(Kategori $kategori)
    {
        return view('apoteker.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi_kategori' => 'nullable|string',
        ]);

        $kategori->update($validated);

        return redirect()->route('apoteker.kategori.index')
            ->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return redirect()->route('apoteker.kategori.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
