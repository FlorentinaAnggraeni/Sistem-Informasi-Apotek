<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Keranjang;
use App\Models\Pelanggan;
use App\Models\Obat;
use App\Services\PaymentService;
use App\Services\NotificationService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PesananController extends Controller
{
    protected $paymentService;
    protected $notificationService;
    protected $imageService;

    public function __construct(PaymentService $paymentService, NotificationService $notificationService, ImageService $imageService)
    {
        $this->paymentService = $paymentService;
        $this->notificationService = $notificationService;
        $this->imageService = $imageService;
    }
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'pelanggan') {
            $pelanggan = $user->pelanggan;
            
            if (!$pelanggan) {
                return redirect()->route('dashboard')->with('error', 'Data pelanggan tidak ditemukan. Silakan hubungi administrator.');
            }
            
            $pesanans = Pesanan::where('id_pelanggan', $pelanggan->id_pelanggan)
                ->with('detailPesanans.obat')
                ->latest()
                ->paginate(10);
            return view('pelanggan.pesanan.index', compact('pesanans'));
        } elseif ($user->role === 'karyawan') {
            $pesanans = Pesanan::with(['pelanggan', 'detailPesanans.obat'])
                ->latest()
                ->paginate(10);
            return view('karyawan.pesanan.index', compact('pesanans'));
        } else {
            $pesanans = Pesanan::with(['pelanggan', 'detailPesanans.obat'])
                ->latest()
                ->paginate(10);
            return view('pesanan.index', compact('pesanans'));
        }
    }

    public function show(Pesanan $pesanan)
    {
        $user = Auth::user();
        $pesanan->load(['pelanggan', 'detailPesanans.obat', 'karyawan']);
        
        if ($user->role === 'pelanggan') {
            return view('pelanggan.pesanan.show', compact('pesanan'));
        } elseif ($user->role === 'karyawan') {
            return view('karyawan.pesanan.show', compact('pesanan'));
        }
        
        return view('pesanan.show', compact('pesanan'));
    }

    public function checkout()
    {
        $pelanggan = Pelanggan::where('id_user', Auth::id())->first();
        
        if (!$pelanggan) {
            return redirect()->route('dashboard')->with('error', 'Data pelanggan tidak ditemukan');
        }

        $keranjangs = Keranjang::where('id_pelanggan', $pelanggan->id_pelanggan)
            ->with('obat')
            ->get();

        if ($keranjangs->isEmpty()) {
            return redirect()->route('pelanggan.keranjang')
                ->with('error', 'Keranjang masih kosong');
        }

        $total = $keranjangs->sum(function($item) {
            return $item->jumlah * $item->obat->harga_obat;
        });

        return view('pelanggan.checkout', compact('keranjangs', 'total', 'pelanggan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'alamat_pengiriman' => 'required|string',
            'catatan' => 'nullable|string',
            'metode_pembayaran' => 'required|in:transfer,cod,e-wallet,qris',
        ]);

        $pelanggan = Pelanggan::where('id_user', Auth::id())->first();
        
        if (!$pelanggan) {
            return back()->with('error', 'Data pelanggan tidak ditemukan');
        }

        $keranjangs = Keranjang::where('id_pelanggan', $pelanggan->id_pelanggan)
            ->with('obat')
            ->get();

        if ($keranjangs->isEmpty()) {
            return back()->with('error', 'Keranjang masih kosong');
        }

        DB::beginTransaction();
        try {
            // Buat pesanan
            $pesanan = Pesanan::create([
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'alamat_pengiriman' => $validated['alamat_pengiriman'],
                'catatan' => $validated['catatan'] ?? null,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'total_nota' => 0,
                'status_pembayaran' => 'pending',
                'status_pengiriman' => 'pending',
                'status_penerimaan' => 'belum diterima',
            ]);

            $total = 0;

            // Buat detail pesanan dari keranjang
            foreach ($keranjangs as $keranjang) {
                // Cek stok
                if (!$keranjang->obat->hasStock($keranjang->jumlah)) {
                    DB::rollBack();
                    return back()->with('error', "Stok {$keranjang->obat->nama_obat} tidak mencukupi");
                }

                $subtotal = $keranjang->jumlah * $keranjang->obat->harga_obat;
                $total += $subtotal;

                DetailPesanan::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_obat' => $keranjang->id_obat,
                    'jumlah' => $keranjang->jumlah,
                    'harga_satuan' => $keranjang->obat->harga_obat,
                    'subtotal' => $subtotal,
                ]);

                // Kurangi stok
                $keranjang->obat->reduceStock($keranjang->jumlah);
            }

            // Update total pesanan
            $pesanan->total_nota = $total;
            $pesanan->save();

            // Process payment berdasarkan metode
            $paymentResult = null;
            switch ($validated['metode_pembayaran']) {
                case 'transfer':
                    $paymentResult = $this->paymentService->processTransfer($pesanan);
                    break;
                case 'e-wallet':
                    $paymentResult = $this->paymentService->processEWallet($pesanan);
                    break;
                case 'qris':
                    $paymentResult = $this->paymentService->processQRIS($pesanan);
                    break;
            }

            // Kosongkan keranjang
            Keranjang::where('id_pelanggan', $pelanggan->id_pelanggan)->delete();

            // Kirim notifikasi
            $this->notificationService->pesananDibuat(Auth::id(), $pesanan);

            DB::commit();

            return redirect()->route('pelanggan.pesanan.payment', $pesanan)
                ->with('success', 'Pesanan berhasil dibuat.')
                ->with('payment_result', $paymentResult);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function uploadBukti(Request $request, Pesanan $pesanan)
    {
        $validated = $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ], [
            'bukti_pembayaran.required' => 'File bukti pembayaran wajib diupload',
            'bukti_pembayaran.file' => 'File tidak valid',
            'bukti_pembayaran.mimes' => 'Format file harus JPG, PNG, atau PDF',
            'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB',
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            try {
                // Hapus bukti lama jika ada
                if ($pesanan->bukti_pembayaran) {
                    $this->imageService->delete($pesanan->bukti_pembayaran);
                }

                $path = $this->imageService->uploadAndCompress($request->file('bukti_pembayaran'), 'bukti-pembayaran', 1000, 85);
                
                $pesanan->bukti_pembayaran = $path;
                $pesanan->status_pembayaran = 'pending'; // Set status ke pending untuk menunggu verifikasi
                $pesanan->save();
            } catch (\Exception $e) {
                return back()->withErrors(['bukti_pembayaran' => 'Gagal upload bukti: ' . $e->getMessage()]);
            }
        }

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }

    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $validated = $request->validate([
            'status_pembayaran' => 'nullable|in:pending,paid,failed',
            'status_pengiriman' => 'nullable|in:pending,shipped,delivered,cancelled',
            'no_resi' => 'nullable|string|max:100',
        ]);

        $oldStatusPembayaran = $pesanan->status_pembayaran;
        $oldStatusPengiriman = $pesanan->status_pengiriman;

        $pesanan->update($validated);

        if ($validated['status_pengiriman'] === 'shipped' && isset($validated['no_resi'])) {
            $pesanan->tanggal_pengiriman = now();
            $pesanan->save();
        }

        // Kirim notifikasi berdasarkan perubahan status
        $pelanggan = $pesanan->pelanggan;
        if ($pelanggan && $pelanggan->user) {
            // Notifikasi pembayaran diterima
            if ($oldStatusPembayaran === 'pending' && $validated['status_pembayaran'] === 'paid') {
                $this->notificationService->pembayaranDiterima($pelanggan->user->id, $pesanan);
            }

            // Notifikasi pesanan dikirim
            if ($oldStatusPengiriman !== 'shipped' && $validated['status_pengiriman'] === 'shipped') {
                $this->notificationService->pesananDikirim($pelanggan->user->id, $pesanan);
            }

            // Notifikasi pesanan sampai
            if ($oldStatusPengiriman !== 'delivered' && $validated['status_pengiriman'] === 'delivered') {
                $this->notificationService->pesananSampai($pelanggan->user->id, $pesanan);
            }
        }

        return back()->with('success', 'Status pesanan berhasil diupdate');
    }

    public function konfirmasiPenerimaan(Pesanan $pesanan)
    {
        $pesanan->status_penerimaan = 'diterima';
        $pesanan->status_pengiriman = 'delivered';
        $pesanan->save();

        return back()->with('success', 'Pesanan telah dikonfirmasi diterima');
    }

    public function payment(Pesanan $pesanan)
    {
        $pesanan->load(['pelanggan', 'detailPesanans.obat']);
        $paymentInstructions = $this->paymentService->getPaymentInstructions($pesanan->metode_pembayaran, $pesanan);
        
        return view('pelanggan.payment', compact('pesanan', 'paymentInstructions'));
    }

    public function verifyPayment(Request $request, Pesanan $pesanan)
    {
        $validated = $request->validate([
            'is_approved' => 'required|in:0,1',
        ]);

        $isApproved = (bool) $validated['is_approved'];
        $result = $this->paymentService->verifyPayment($pesanan, $isApproved);

        if ($result['success']) {
            $pelanggan = $pesanan->pelanggan;
            if ($pelanggan && $pelanggan->user) {
                $this->notificationService->pembayaranDiterima($pelanggan->user->id, $pesanan);
            }
        }

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}
