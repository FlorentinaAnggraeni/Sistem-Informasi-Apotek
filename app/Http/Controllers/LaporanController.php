<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Transaksi;
use App\Models\Obat;
use App\Models\Pelanggan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class LaporanController extends Controller
{
    public function index()
    {
        return view('pemilik.laporan.index');
    }

    public function penjualan(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth());
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->endOfMonth());

        $pesanans = Pesanan::with(['pelanggan', 'detailPesanans.obat'])
            ->whereBetween('created_at', [$tanggalMulai, $tanggalAkhir])
            ->where('status_pembayaran', 'paid')
            ->get();

        $totalPenjualan = $pesanans->sum('total_nota');
        $jumlahTransaksi = $pesanans->count();

        // Penjualan per hari
        $penjualanPerHari = $pesanans->groupBy(function($item) {
            return Carbon::parse($item->created_at)->format('Y-m-d');
        })->map(function($items) {
            return [
                'total' => $items->sum('total_nota'),
                'count' => $items->count(),
            ];
        });

        // Obat terlaris
        $obatTerlaris = DB::table('detail_pesanans')
            ->join('pesanans', 'detail_pesanans.id_pesanan', '=', 'pesanans.id_pesanan')
            ->join('obats', 'detail_pesanans.id_obat', '=', 'obats.id_obat')
            ->whereBetween('pesanans.created_at', [$tanggalMulai, $tanggalAkhir])
            ->where('pesanans.status_pembayaran', 'paid')
            ->select('obats.nama_obat', DB::raw('SUM(detail_pesanans.jumlah) as total_terjual'))
            ->groupBy('obats.id_obat', 'obats.nama_obat')
            ->orderByDesc('total_terjual')
            ->limit(10)
            ->get();

        return view('pemilik.laporan.penjualan', compact(
            'pesanans',
            'totalPenjualan',
            'jumlahTransaksi',
            'penjualanPerHari',
            'obatTerlaris',
            'tanggalMulai',
            'tanggalAkhir'
        ));
    }

    public function stok()
    {
        $obats = Obat::with(['kategori', 'supplier'])
            ->orderBy('stok_obat', 'asc')
            ->get();

        $stokRendah = $obats->where('stok_obat', '<=', 10)->where('stok_obat', '>', 0);
        $stokHabis = $obats->where('stok_obat', '=', 0);
        $totalNilaiStok = $obats->sum(function($obat) {
            return $obat->stok_obat * $obat->harga_obat;
        });

        return view('pemilik.laporan.stok', compact('obats', 'stokRendah', 'stokHabis', 'totalNilaiStok'));
    }

    public function pelanggan()
    {
        $pelanggans = Pelanggan::with('pesanans')->get();

        $pelangganAktif = $pelanggans->filter(function($pelanggan) {
            return $pelanggan->pesanans->where('created_at', '>=', Carbon::now()->subMonths(3))->count() > 0;
        });

        $topPelanggan = $pelanggans->sortByDesc(function($pelanggan) {
            return $pelanggan->pesanans->where('status_pembayaran', 'paid')->sum('total_nota');
        })->take(10);

        return view('pemilik.laporan.pelanggan', compact('pelanggans', 'pelangganAktif', 'topPelanggan'));
    }

    public function keuangan(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth());
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->endOfMonth());

        $pemasukan = Pesanan::whereBetween('created_at', [$tanggalMulai, $tanggalAkhir])
            ->where('status_pembayaran', 'paid')
            ->sum('total_nota');

        // Ini contoh, sesuaikan dengan kebutuhan
        $pengeluaran = Karyawan::sum('gaji'); // Gaji karyawan

        $laba = $pemasukan - $pengeluaran;

        return view('pemilik.laporan.keuangan', compact(
            'pemasukan',
            'pengeluaran',
            'laba',
            'tanggalMulai',
            'tanggalAkhir'
        ));
    }

    public function exportExcel(Request $request)
    {
        // Tentukan tipe laporan dari query parameter
        $type = $request->input('type', 'stok'); // Default: stok
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth());
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->endOfMonth());

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        if ($type === 'penjualan') {
            return $this->exportPenjualanExcel($sheet, $tanggalMulai, $tanggalAkhir, $spreadsheet);
        } elseif ($type === 'stok') {
            return $this->exportStokExcel($sheet, $spreadsheet);
        } elseif ($type === 'pelanggan') {
            return $this->exportPelangganExcel($sheet, $spreadsheet);
        } elseif ($type === 'keuangan') {
            return $this->exportKeuanganExcel($sheet, $tanggalMulai, $tanggalAkhir, $spreadsheet);
        }

        return back()->with('error', 'Tipe laporan tidak valid');
    }

    private function exportStokExcel($sheet, $spreadsheet)
    {
        $sheet->setTitle('Laporan Stok');

        // Header
        $sheet->setCellValue('A1', 'LAPORAN STOK OBAT');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Tanggal: ' . now()->format('d-m-Y H:i:s'));
        $sheet->mergeCells('A2:H2');

        // Headers tabel
        $headers = ['No', 'Kode/Batch', 'Nama Obat', 'Jenis', 'Kategori', 'Supplier', 'Stok', 'Harga', 'Nilai Stok'];
        $columnLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

        for ($i = 0; $i < count($headers); $i++) {
            $sheet->setCellValue($columnLetters[$i] . '4', $headers[$i]);
            $sheet->getStyle($columnLetters[$i] . '4')->getFont()->setBold(true)->setColor(new Color('FFFFFFFF'));
            $sheet->getStyle($columnLetters[$i] . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4472C4');
            $sheet->getStyle($columnLetters[$i] . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Data obat
        $obats = Obat::with(['kategori', 'supplier'])->orderBy('nama_obat')->get();
        $row = 5;
        $no = 1;

        foreach ($obats as $obat) {
            $nilaiStok = $obat->stok_obat * $obat->harga_obat;

            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $obat->no_batch ?? '-');
            $sheet->setCellValue('C' . $row, $obat->nama_obat);
            $sheet->setCellValue('D' . $row, $obat->jenis_obat);
            $sheet->setCellValue('E' . $row, $obat->kategori->nama_kategori ?? '-');
            $sheet->setCellValue('F' . $row, $obat->supplier->nama_supplier ?? '-');
            $sheet->setCellValue('G' . $row, $obat->stok_obat);
            $sheet->setCellValue('H' . $row, $obat->harga_obat);
            $sheet->setCellValue('I' . $row, $nilaiStok);

            // Format harga dan nilai stok sebagai currency
            $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('I' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

            // Center untuk nomor dan stok
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
            $no++;
        }

        // Total
        $lastRow = $row - 1;
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->mergeCells('A' . $row . ':G' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $sheet->setCellValue('I' . $row, '=SUM(I5:I' . $lastRow . ')');
        $sheet->getStyle('I' . $row)->getFont()->setBold(true);
        $sheet->getStyle('I' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

        // Set column width
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getColumnDimension('H')->setWidth(12);
        $sheet->getColumnDimension('I')->setWidth(15);

        // Write file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Laporan-Stok-' . now()->format('d-m-Y-His') . '.xlsx';
        
        ob_start();
        $writer->save('php://output');
        $content = ob_get_contents();
        ob_end_clean();
        
        return response($content)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->header('Cache-Control', 'max-age=0')
            ->header('Pragma', 'public');
    }

    private function exportPenjualanExcel($sheet, $tanggalMulai, $tanggalAkhir, $spreadsheet)
    {
        $sheet->setTitle('Laporan Penjualan');

        // Header
        $sheet->setCellValue('A1', 'LAPORAN PENJUALAN');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Periode: ' . Carbon::parse($tanggalMulai)->format('d-m-Y') . ' s/d ' . Carbon::parse($tanggalAkhir)->format('d-m-Y'));
        $sheet->mergeCells('A2:F2');

        // Headers tabel
        $headers = ['No', 'Tanggal', 'No Pesanan', 'Pelanggan', 'Total', 'Status'];
        $columnLetters = ['A', 'B', 'C', 'D', 'E', 'F'];

        for ($i = 0; $i < count($headers); $i++) {
            $sheet->setCellValue($columnLetters[$i] . '4', $headers[$i]);
            $sheet->getStyle($columnLetters[$i] . '4')->getFont()->setBold(true)->setColor(new Color('FFFFFFFF'));
            $sheet->getStyle($columnLetters[$i] . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4472C4');
            $sheet->getStyle($columnLetters[$i] . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Data penjualan
        $pesanans = Pesanan::with(['pelanggan', 'detailPesanans.obat'])
            ->whereBetween('created_at', [$tanggalMulai, $tanggalAkhir])
            ->where('status_pembayaran', 'paid')
            ->orderByDesc('created_at')
            ->get();

        $row = 5;
        $no = 1;
        $totalPenjualan = 0;

        foreach ($pesanans as $pesanan) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, Carbon::parse($pesanan->created_at)->format('d-m-Y'));
            $sheet->setCellValue('C' . $row, $pesanan->id_pesanan);
            $sheet->setCellValue('D' . $row, $pesanan->pelanggan->nama_pelanggan ?? '-');
            $sheet->setCellValue('E' . $row, $pesanan->total_nota);
            $sheet->setCellValue('F' . $row, ucfirst($pesanan->status_pembayaran));

            $sheet->getStyle('E' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $totalPenjualan += $pesanan->total_nota;
            $row++;
            $no++;
        }

        // Total
        $lastRow = $row - 1;
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->mergeCells('A' . $row . ':D' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $sheet->setCellValue('E' . $row, $totalPenjualan);
        $sheet->getStyle('E' . $row)->getFont()->setBold(true);
        $sheet->getStyle('E' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

        // Set column width
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Laporan-Penjualan-' . now()->format('d-m-Y-His') . '.xlsx';

        ob_start();
        $writer->save('php://output');
        $content = ob_get_contents();
        ob_end_clean();
        
        return response($content)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->header('Cache-Control', 'max-age=0')
            ->header('Pragma', 'public');
    }

    private function exportPelangganExcel($sheet, $spreadsheet)
    {
        $sheet->setTitle('Laporan Pelanggan');

        $sheet->setCellValue('A1', 'LAPORAN DATA PELANGGAN');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Tanggal: ' . now()->format('d-m-Y H:i:s'));
        $sheet->mergeCells('A2:F2');

        // Headers
        $headers = ['No', 'Nama Pelanggan', 'Email', 'No Telepon', 'Alamat', 'Total Pembelian'];
        $columnLetters = ['A', 'B', 'C', 'D', 'E', 'F'];

        for ($i = 0; $i < count($headers); $i++) {
            $sheet->setCellValue($columnLetters[$i] . '4', $headers[$i]);
            $sheet->getStyle($columnLetters[$i] . '4')->getFont()->setBold(true)->setColor(new Color('FFFFFFFF'));
            $sheet->getStyle($columnLetters[$i] . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4472C4');
        }

        $pelanggans = Pelanggan::with('pesanans')->orderBy('nama_pelanggan')->get();
        $row = 5;
        $no = 1;

        foreach ($pelanggans as $pelanggan) {
            $totalBeli = $pelanggan->pesanans()->where('status_pembayaran', 'paid')->sum('total_nota');

            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $pelanggan->nama_pelanggan);
            $sheet->setCellValue('C' . $row, $pelanggan->email_pelanggan ?? '-');
            $sheet->setCellValue('D' . $row, $pelanggan->no_telp_pelanggan ?? '-');
            $sheet->setCellValue('E' . $row, $pelanggan->alamat_pelanggan ?? '-');
            $sheet->setCellValue('F' . $row, $totalBeli);

            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
            $no++;
        }

        // Set column width
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setWidth(18);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Laporan-Pelanggan-' . now()->format('d-m-Y-His') . '.xlsx';

        ob_start();
        $writer->save('php://output');
        $content = ob_get_contents();
        ob_end_clean();
        
        return response($content)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->header('Cache-Control', 'max-age=0')
            ->header('Pragma', 'public');
    }

    private function exportKeuanganExcel($sheet, $tanggalMulai, $tanggalAkhir, $spreadsheet)
    {
        $sheet->setTitle('Laporan Keuangan');

        $sheet->setCellValue('A1', 'LAPORAN KEUANGAN');
        $sheet->mergeCells('A1:B1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $sheet->setCellValue('A2', 'Periode: ' . Carbon::parse($tanggalMulai)->format('d-m-Y') . ' s/d ' . Carbon::parse($tanggalAkhir)->format('d-m-Y'));
        $sheet->mergeCells('A2:B2');

        $pemasukan = Pesanan::whereBetween('created_at', [$tanggalMulai, $tanggalAkhir])
            ->where('status_pembayaran', 'paid')
            ->sum('total_nota');

        $pengeluaran = Karyawan::sum('gaji') ?? 0;
        $laba = $pemasukan - $pengeluaran;

        // Data keuangan
        $sheet->setCellValue('A4', 'Pemasukan');
        $sheet->setCellValue('B4', $pemasukan);
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->getStyle('B4')->getNumberFormat()->setFormatCode('#,##0.00');

        $sheet->setCellValue('A5', 'Pengeluaran (Gaji Karyawan)');
        $sheet->setCellValue('B5', $pengeluaran);
        $sheet->getStyle('A5')->getFont()->setBold(true);
        $sheet->getStyle('B5')->getNumberFormat()->setFormatCode('#,##0.00');

        $sheet->setCellValue('A6', 'Laba Bersih');
        $sheet->setCellValue('B6', $laba);
        $sheet->getStyle('A6')->getFont()->setBold(true)->setColor(new Color('FFFFFFFF'));
        $sheet->getStyle('A6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('70AD47');
        $sheet->getStyle('B6')->getFont()->setBold(true)->setColor(new Color('FFFFFFFF'));
        $sheet->getStyle('B6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('70AD47');
        $sheet->getStyle('B6')->getNumberFormat()->setFormatCode('#,##0.00');

        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(20);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Laporan-Keuangan-' . now()->format('d-m-Y-His') . '.xlsx';

        ob_start();
        $writer->save('php://output');
        $content = ob_get_contents();
        ob_end_clean();
        
        return response($content)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->header('Cache-Control', 'max-age=0')
            ->header('Pragma', 'public');
    }
}
