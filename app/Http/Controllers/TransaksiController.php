<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pesanan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['pesanan.pelanggan', 'karyawan'])
            ->latest()
            ->paginate(10);

        return view('karyawan.transaksi.index', compact('transaksis'));
    }

    public function create(Pesanan $pesanan)
    {
        $pesanan->load(['pelanggan', 'detailPesanans.obat']);
        
        // Cek apakah pesanan sudah lunas
        if ($pesanan->status_pembayaran !== 'paid') {
            return back()->with('error', 'Pesanan belum lunas');
        }

        return view('karyawan.transaksi.create', compact('pesanan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pesanan' => 'required|exists:pesanans,id_pesanan',
            'metode_pembayaran' => 'required|in:cash,transfer,e-wallet,qris',
            'keterangan' => 'nullable|string',
        ]);

        $pesanan = Pesanan::findOrFail($validated['id_pesanan']);
        
        // Cek apakah pesanan sudah lunas
        if ($pesanan->status_pembayaran !== 'paid') {
            return back()->with('error', 'Pesanan belum lunas');
        }

        // Dapatkan karyawan yang sedang login
        $karyawan = Karyawan::where('id_user', Auth::id())->first();

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::create([
                'id_pesanan' => $validated['id_pesanan'],
                'id_karyawan' => $karyawan ? $karyawan->id_karyawan : null,
                'tanggal_transaksi' => now(),
                'total_transaksi' => $pesanan->total_nota,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status_transaksi' => 'success',
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('karyawan.transaksi.show', $transaksi)
                ->with('success', 'Transaksi berhasil dicatat');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['pesanan.pelanggan', 'pesanan.detailPesanans.obat', 'karyawan']);
        return view('karyawan.transaksi.show', compact('transaksi'));
    }

    public function updateStatus(Request $request, Transaksi $transaksi)
    {
        $validated = $request->validate([
            'status_transaksi' => 'required|in:pending,success,failed',
        ]);

        $transaksi->update($validated);

        return back()->with('success', 'Status transaksi berhasil diupdate');
    }

    // Laporan transaksi harian
    public function laporanHarian()
    {
        $transaksis = Transaksi::with(['pesanan.pelanggan', 'karyawan'])
            ->whereDate('tanggal_transaksi', today())
            ->get();

        $totalTransaksi = $transaksis->where('status_transaksi', 'success')->sum('total_transaksi');
        $jumlahTransaksi = $transaksis->where('status_transaksi', 'success')->count();

        return view('karyawan.transaksi.laporan-harian', compact('transaksis', 'totalTransaksi', 'jumlahTransaksi'));
    }
}
