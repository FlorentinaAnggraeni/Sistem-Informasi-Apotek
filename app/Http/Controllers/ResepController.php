<?php

namespace App\Http\Controllers;

use App\Models\Resep;
use App\Models\DetailResep;
use App\Models\Obat;
use App\Models\Pelanggan;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ResepController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    {
        $query = Resep::with(['pelanggan', 'apoteker', 'detailReseps.obat']);
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $reseps = $query->latest()->paginate(15);
        
        return view('apoteker.resep.index', compact('reseps'));
    }

    public function show($id)
    {
        $resep = Resep::with(['pelanggan.user', 'apoteker', 'detailReseps.obat'])
            ->findOrFail($id);
        
        return view('apoteker.resep.show', compact('resep'));
    }

    public function proses($id)
    {
        $resep = Resep::with(['pelanggan'])->findOrFail($id);
        $obats = Obat::where('stok_obat', '>', 0)->orderBy('nama_obat')->get();
        
        return view('apoteker.resep.proses', compact('resep', 'obats'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:diproses,selesai,ditolak',
            'catatan_apoteker' => 'nullable|string',
            'obat' => 'nullable|array',
            'obat.*.id_obat' => 'required_with:obat|exists:obats,id_obat',
            'obat.*.jumlah' => 'required_with:obat|integer|min:1',
            'obat.*.aturan_pakai' => 'required_with:obat|string',
            'obat.*.catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $resep = Resep::findOrFail($id);
            
            $karyawan = Auth::user()->karyawan;
            
            // Update status resep
            $resep->update([
                'status' => $validated['status'],
                'catatan_apoteker' => $validated['catatan_apoteker'] ?? null,
                'id_apoteker' => $karyawan ? $karyawan->id_karyawan : null,
            ]);

            // Jika status diproses atau selesai, simpan detail obat
            if (in_array($validated['status'], ['diproses', 'selesai']) && !empty($validated['obat'])) {
                // Hapus detail lama jika ada
                $resep->detailReseps()->delete();
                
                foreach ($validated['obat'] as $obatData) {
                    if (!empty($obatData['id_obat']) && !empty($obatData['jumlah']) && !empty($obatData['aturan_pakai'])) {
                        DetailResep::create([
                            'id_resep' => $resep->id_resep,
                            'id_obat' => $obatData['id_obat'],
                            'jumlah' => $obatData['jumlah'],
                            'aturan_pakai' => $obatData['aturan_pakai'],
                            'catatan' => $obatData['catatan'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('apoteker.resep.index')
                ->with('success', 'Resep berhasil diproses');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal memproses resep: ' . $e->getMessage()]);
        }
    }

    // Methods untuk Pelanggan
    public function pelangganIndex(Request $request)
    {
        $pelanggan = Auth::user()->pelanggan;
        $query = Resep::where('id_pelanggan', $pelanggan->id_pelanggan)
            ->with(['apoteker', 'detailReseps.obat']);
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $reseps = $query->latest()->paginate(10);
        
        return view('pelanggan.resep.index', compact('reseps'));
    }

    public function create()
    {
        return view('pelanggan.resep.create');
    }

    public function pelangganStore(Request $request)
    {
        $validated = $request->validate([
            'nama_dokter' => 'required|string|max:100',
            'nama_pasien' => 'required|string|max:100',
            'diagnosa' => 'required|string',
            'foto_resep' => 'required|image|mimes:jpeg,png,jpg|max:5120', // max 5MB
            'tanggal_resep' => 'required|date',
        ]);

        $pelanggan = Auth::user()->pelanggan;
        
        // Generate nomor resep yang unik
        $date = date('Ymd');
        $lastResep = Resep::where('no_resep', 'like', "RSP-{$date}-%")
            ->orderBy('no_resep', 'desc')
            ->first();
        
        if ($lastResep) {
            $lastNumber = (int) substr($lastResep->no_resep, -3);
            $number = $lastNumber + 1;
        } else {
            $number = 1;
        }
        
        $noResep = 'RSP-' . $date . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);

        // Upload foto resep dengan compression
        if ($request->hasFile('foto_resep')) {
            try {
                $validated['foto_resep'] = $this->imageService->uploadAndCompress($request->file('foto_resep'), 'resep', 1200, 85);
            } catch (\Exception $e) {
                return back()->withErrors(['foto_resep' => 'Gagal upload foto resep: ' . $e->getMessage()]);
            }
        }

        Resep::create([
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'no_resep' => $noResep,
            'nama_dokter' => $validated['nama_dokter'],
            'nama_pasien' => $validated['nama_pasien'],
            'diagnosa' => $validated['diagnosa'],
            'foto_resep' => $validated['foto_resep'],
            'status' => 'pending',
            'tanggal_resep' => $validated['tanggal_resep'],
        ]);

        return redirect()->route('pelanggan.resep.index')
            ->with('success', 'Resep berhasil diupload. Menunggu validasi apoteker.');
    }

    public function pelangganShow($id)
    {
        $pelanggan = Auth::user()->pelanggan;
        $resep = Resep::where('id_pelanggan', $pelanggan->id_pelanggan)
            ->where('id_resep', $id)
            ->with(['apoteker', 'detailReseps.obat'])
            ->firstOrFail();
        
        return view('pelanggan.resep.show', compact('resep'));
    }
}
