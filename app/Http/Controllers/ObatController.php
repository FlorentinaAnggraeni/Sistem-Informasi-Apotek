<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ObatController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $user = Auth::user();
        
        $obats = Obat::with(['kategori', 'supplier'])
            ->latest()
            ->paginate(10);
        
        // Tentukan view berdasarkan role
        if ($user->role === 'karyawan') {
            return view('karyawan.obat.index', compact('obats'));
        }
        
        return view('apoteker.obat.index', compact('obats'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        $suppliers = Supplier::all();
        
        return view('apoteker.obat.create', compact('kategoris', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:100',
            'jenis_obat' => 'required|string|max:50',
            'satuan' => 'required|string|max:20',
            'deskripsi_obat' => 'nullable|string',
            'harga_obat' => 'required|numeric|min:0',
            'stok_obat' => 'required|integer|min:0',
            'id_kategori' => 'nullable|exists:kategoris,id_kategori',
            'id_supplier' => 'nullable|exists:suppliers,id_supplier',
            'gambar_obat' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tanggal_kadaluarsa' => 'nullable|date',
            'no_batch' => 'nullable|string|max:50',
        ]);

        if ($request->hasFile('gambar_obat')) {
            try {
                $validated['gambar_obat'] = $this->imageService->uploadAndCompress($request->file('gambar_obat'), 'obat');
            } catch (\Exception $e) {
                return back()->withErrors(['gambar_obat' => 'Gagal upload gambar: ' . $e->getMessage()]);
            }
        }

        Obat::create($validated);

        return redirect()->route('apoteker.obat.index')
            ->with('success', 'Obat berhasil ditambahkan');
    }

    public function show(Obat $obat)
    {
        $obat->load(['kategori', 'supplier']);
        return view('apoteker.obat.show', compact('obat'));
    }

    public function edit(Obat $obat)
    {
        $kategoris = Kategori::all();
        $suppliers = Supplier::all();
        
        return view('apoteker.obat.edit', compact('obat', 'kategoris', 'suppliers'));
    }

    public function update(Request $request, Obat $obat)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:100',
            'jenis_obat' => 'required|string|max:50',
            'satuan' => 'required|string|max:20',
            'deskripsi_obat' => 'nullable|string',
            'harga_obat' => 'required|numeric|min:0',
            'stok_obat' => 'required|integer|min:0',
            'id_kategori' => 'nullable|exists:kategoris,id_kategori',
            'id_supplier' => 'nullable|exists:suppliers,id_supplier',
            'gambar_obat' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tanggal_kadaluarsa' => 'nullable|date',
            'no_batch' => 'nullable|string|max:50',
        ]);

        if ($request->hasFile('gambar_obat')) {
            try {
                // Hapus gambar lama
                if ($obat->gambar_obat) {
                    $this->imageService->delete($obat->gambar_obat);
                }
                $validated['gambar_obat'] = $this->imageService->uploadAndCompress($request->file('gambar_obat'), 'obat');
            } catch (\Exception $e) {
                return back()->withErrors(['gambar_obat' => 'Gagal upload gambar: ' . $e->getMessage()]);
            }
        }

        $obat->update($validated);

        return redirect()->route('apoteker.obat.index')
            ->with('success', 'Obat berhasil diupdate');
    }

    public function destroy(Obat $obat)
    {
        // Hapus gambar jika ada
        if ($obat->gambar_obat) {
            $this->imageService->delete($obat->gambar_obat);
        }

        $obat->delete();

        return redirect()->route('apoteker.obat.index')
            ->with('success', 'Obat berhasil dihapus');
    }

    // Stok management
    public function stok()
    {
        $obats = Obat::with(['kategori', 'supplier'])
            ->orderBy('stok_obat', 'asc')
            ->paginate(20);
        
        return view('apoteker.stok.index', compact('obats'));
    }

    public function updateStok(Request $request, Obat $obat)
    {
        $validated = $request->validate([
            'stok_obat' => 'required|integer|min:0',
        ]);

        $obat->update($validated);

        return back()->with('success', 'Stok berhasil diupdate');
    }
}
