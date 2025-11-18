<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ObatController extends Controller
{
    /**
     * Menampilkan daftar obat
     */
    public function index(Request $request)
    {
        $query = Obat::query();

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('kode_obat', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter stok menipis
        if ($request->has('stok_menipis') && $request->stok_menipis == '1') {
            $query->stokMenipis();
        }

        // Filter mendekati kadaluarsa
        if ($request->has('mendekati_kadaluarsa') && $request->mendekati_kadaluarsa == '1') {
            $query->mendekatiKadaluarsa();
        }

        $obat = $query->latest()->paginate(10);
        
        // Hitung statistik
        $totalObat = Obat::aktif()->count();
        $stokMenipis = Obat::stokMenipis()->count();
        $mendekatiKadaluarsa = Obat::mendekatiKadaluarsa()->count();
        $kadaluarsa = Obat::kadaluarsa()->count();

        return view('obat.index', compact('obat', 'totalObat', 'stokMenipis', 'mendekatiKadaluarsa', 'kadaluarsa'));
    }

    /**
     * Menampilkan form tambah obat
     */
    public function create()
    {
        $kodeObat = $this->generateKodeObat();
        return view('obat.create', compact('kodeObat'));
    }

    /**
     * Menyimpan data obat baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_obat' => 'required|string|unique:obat,kode_obat|max:50',
            'nama_obat' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'jenis' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'tanggal_kadaluarsa' => 'nullable|date|after:today',
            'supplier' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,non-aktif'
        ]);

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('obat', 'public');
        }

        Obat::create($validated);

        return redirect()->route('obat.index')
            ->with('success', 'Data obat berhasil ditambahkan');
    }

    /**
     * Menampilkan detail obat
     */
    public function show(Obat $obat)
    {
        return view('obat.show', compact('obat'));
    }

    /**
     * Menampilkan form edit obat
     */
    public function edit(Obat $obat)
    {
        return view('obat.edit', compact('obat'));
    }

    /**
     * Update data obat
     */
    public function update(Request $request, Obat $obat)
    {
        $validated = $request->validate([
            'kode_obat' => 'required|string|max:50|unique:obat,kode_obat,' . $obat->id,
            'nama_obat' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'jenis' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'tanggal_kadaluarsa' => 'nullable|date|after:today',
            'supplier' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,non-aktif'
        ]);

        // Upload foto baru jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($obat->foto) {
                Storage::disk('public')->delete($obat->foto);
            }
            $validated['foto'] = $request->file('foto')->store('obat', 'public');
        }

        $obat->update($validated);

        return redirect()->route('obat.index')
            ->with('success', 'Data obat berhasil diupdate');
    }

    /**
     * Hapus data obat
     */
    public function destroy(Obat $obat)
    {
        // Cek apakah obat pernah digunakan dalam transaksi
        if ($obat->detailPesanan()->exists()) {
            return redirect()->route('obat.index')
                ->with('error', 'Obat tidak dapat dihapus karena sudah digunakan dalam transaksi');
        }

        // Hapus foto jika ada
        if ($obat->foto) {
            Storage::disk('public')->delete($obat->foto);
        }

        $obat->delete();

        return redirect()->route('obat.index')
            ->with('success', 'Data obat berhasil dihapus');
    }

    /**
     * Tambah stok obat
     */
    public function tambahStok(Request $request, Obat $obat)
    {
        $validated = $request->validate([
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $obat->stok += $validated['jumlah'];
        $obat->save();

        // Log aktivitas (opsional, bisa ditambahkan tabel log_stok)

        return redirect()->route('obat.show', $obat)
            ->with('success', "Stok berhasil ditambah sebanyak {$validated['jumlah']} {$obat->satuan}");
    }

    /**
     * Kurangi stok obat
     */
    public function kurangiStok(Request $request, Obat $obat)
    {
        $validated = $request->validate([
            'jumlah' => 'required|integer|min:1|max:' . $obat->stok,
            'keterangan' => 'nullable|string|max:255'
        ]);

        $obat->stok -= $validated['jumlah'];
        $obat->save();

        // Log aktivitas (opsional)

        return redirect()->route('obat.show', $obat)
            ->with('success', "Stok berhasil dikurangi sebanyak {$validated['jumlah']} {$obat->satuan}");
    }

    /**
     * Generate kode obat otomatis
     */
    private function generateKodeObat()
    {
        $prefix = 'OBT';
        $date = date('Ymd');
        $lastObat = Obat::whereDate('created_at', today())
                        ->orderBy('id', 'desc')
                        ->first();

        if ($lastObat) {
            $lastNumber = (int) substr($lastObat->kode_obat, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $date . $newNumber;
    }

    /**
     * Export data obat (opsional)
     */
    public function export()
    {
        // Implementasi export ke Excel/PDF
        // Bisa menggunakan package seperti Laravel Excel
    }
}