<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Obat;
use App\Models\Keranjang;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        switch ($user->role) {
            case 'pemilik':
                // Ambil data untuk dashboard pemilik
                $pendapatanHariIni = Transaksi::whereDate('created_at', Carbon::today())
                    ->sum('total_transaksi');
                $transaksiHariIni = Transaksi::whereDate('created_at', Carbon::today())->count();
                $totalPelanggan = Pelanggan::count();
                $totalProduk = Obat::count();
                
                // Pendapatan per bulan (12 bulan terakhir)
                $pendapatanBulanan = [];
                $bulanLabels = [];
                for ($i = 11; $i >= 0; $i--) {
                    $bulan = Carbon::now()->subMonths($i);
                    $bulanLabels[] = $bulan->format('M');
                    $pendapatanBulanan[] = Transaksi::whereYear('created_at', $bulan->year)
                        ->whereMonth('created_at', $bulan->month)
                        ->sum('total_transaksi') / 1000000; // Convert to millions
                }
                
                // Kategori produk terlaris
                $kategoriTerlaris = DB::table('detail_pesanans')
                    ->join('obats', 'detail_pesanans.id_obat', '=', 'obats.id_obat')
                    ->join('kategoris', 'obats.id_kategori', '=', 'kategoris.id_kategori')
                    ->select('kategoris.nama_kategori', DB::raw('SUM(detail_pesanans.jumlah) as total'))
                    ->groupBy('kategoris.id_kategori', 'kategoris.nama_kategori')
                    ->orderByDesc('total')
                    ->limit(4)
                    ->get();
                
                return view('dashboard.pemilik', compact(
                    'pendapatanHariIni',
                    'transaksiHariIni',
                    'totalPelanggan',
                    'totalProduk',
                    'pendapatanBulanan',
                    'bulanLabels',
                    'kategoriTerlaris'
                ));
            case 'apoteker':
                // Ambil data untuk dashboard apoteker
                $totalObat = Obat::count();
                $stokMenurun = Obat::where('stok_obat', '<', 10)->count();
                $stokHabis = Obat::where('stok_obat', '=', 0)->count();
                $totalKategori = \App\Models\Kategori::count();
                
                // Obat yang perlu restock
                $obatPerluRestock = Obat::where('stok_obat', '<', 10)
                    ->orderBy('stok_obat', 'asc')
                    ->limit(10)
                    ->get();
                
                return view('dashboard.apoteker', compact(
                    'totalObat',
                    'stokMenurun',
                    'stokHabis',
                    'totalKategori',
                    'obatPerluRestock'
                ));
            case 'karyawan':
                // Ambil data untuk dashboard karyawan
                $pesananBaru = Pesanan::where('status_pembayaran', 'pending')->count();
                $sedangDiproses = Pesanan::where('status_pengiriman', 'shipped')->count();
                $selesaiHariIni = Pesanan::where('status_pengiriman', 'delivered')
                    ->whereDate('updated_at', Carbon::today())
                    ->count();
                $pesananTerbaru = Pesanan::with('pelanggan.user')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
                
                return view('dashboard.karyawan', compact('pesananBaru', 'sedangDiproses', 'selesaiHariIni', 'pesananTerbaru'));
            case 'pelanggan':
            default:
                // Ambil data untuk dashboard pelanggan
                $pelanggan = $user->pelanggan;
                $totalProduk = Obat::count();
                $itemKeranjang = $pelanggan ? Keranjang::where('id_pelanggan', $pelanggan->id_pelanggan)->count() : 0;
                $pesananAktif = $pelanggan ? Pesanan::where('id_pelanggan', $pelanggan->id_pelanggan)
                    ->whereIn('status_pengiriman', ['pending', 'shipped'])
                    ->count() : 0;
                $pesananSelesai = $pelanggan ? Pesanan::where('id_pelanggan', $pelanggan->id_pelanggan)
                    ->where('status_pengiriman', 'delivered')
                    ->count() : 0;
                
                return view('dashboard.pelanggan', compact('totalProduk', 'itemKeranjang', 'pesananAktif', 'pesananSelesai'));
        }
    }

    // === PELANGGAN ROUTES ===
    public function produkPelanggan()
    {
        return view('pelanggan.produk');
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
        $user = Auth::user();
        $pelanggan = $user->pelanggan;
        
        return view('pelanggan.profil.index', compact('user', 'pelanggan'));
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        $pelanggan = $user->pelanggan;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'password' => 'nullable|min:6|confirmed',
        ]);

        // Update user data
        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->no_hp = $validated['no_hp'] ?? null;
        $user->alamat = $validated['alamat'] ?? null;
        // Update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Prepare update data for users table
        $updateData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
        ];
        if (isset($validated['password'])) {
            $updateData['password'] = $validated['password'];
        }

        // If $user is an Eloquent model instance (App\Models\User), use save(); otherwise perform a query update
        if ($user instanceof User) {
            $user->name = $updateData['name'];
            $user->username = $updateData['username'];
            $user->email = $updateData['email'];
            $user->no_hp = $updateData['no_hp'];
            $user->alamat = $updateData['alamat'];
            if (isset($updateData['password'])) {
                $user->password = $updateData['password'];
            }
            $user->save();
        } else {
            // Fallback: update via the User model (query builder)
            User::where('id', $user->id)->update($updateData);
        }

        // Update pelanggan data if exists
        if ($pelanggan) {
            $pelanggan->nama_pelanggan = $validated['name'];
            $pelanggan->alamat = $validated['alamat'] ?? null;
            $pelanggan->no_telepon = $validated['no_hp'] ?? null;
            $pelanggan->save();
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    // === PEMILIK ROUTES ===
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
    public function kelolaObat()
    {
        return view('apoteker.kelola-obat');
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
    public function transaksi()
    {
        return view('karyawan.transaksi');
    }

    public function pesananMasuk()
    {
        return view('karyawan.pesanan-masuk');
    }
}