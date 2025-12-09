<?php

namespace App\Services;

use App\Models\Pesanan;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Simulasi pembayaran transfer bank
     */
    public function processTransfer(Pesanan $pesanan, $buktiPembayaran = null)
    {
        $pesanan->update([
            'metode_pembayaran' => 'transfer',
            'bukti_pembayaran' => $buktiPembayaran,
            'status_pembayaran' => 'pending', // Menunggu verifikasi admin
        ]);

        return [
            'success' => true,
            'message' => 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.',
            'payment_method' => 'transfer',
        ];
    }

    /**
     * Simulasi pembayaran E-Wallet (GoPay, OVO, DANA, dll)
     */
    public function processEWallet(Pesanan $pesanan)
    {
        // Generate virtual account number
        $vaNumber = '8888' . str_pad($pesanan->id_pesanan, 10, '0', STR_PAD_LEFT);
        
        // Generate payment code
        $paymentCode = 'EW-' . strtoupper(Str::random(10));

        // Update pesanan dengan VA number dan status pending
        $pesanan->update([
            'metode_pembayaran' => 'e-wallet',
            'status_pembayaran' => 'pending', // Menunggu upload bukti
            'payment_code' => $paymentCode,
            'va_number' => $vaNumber,
        ]);

        return [
            'success' => true,
            'message' => 'Silakan lakukan pembayaran melalui E-Wallet dan upload bukti pembayaran.',
            'payment_method' => 'e-wallet',
            'payment_code' => $paymentCode,
            'va_number' => $vaNumber,
            'amount' => $pesanan->total_nota,
        ];
    }

    /**
     * Simulasi pembayaran QRIS
     */
    public function processQRIS(Pesanan $pesanan)
    {
        // Generate EMV QRIS string (simulasi)
        // Format: 00020101021226... (POI Method for QRIS)
        $merchantId = '00000000000001'; // Simulasi Merchant ID
        $merchantName = 'APOTEK'; // Nama merchant
        $amount = $pesanan->total_nota;
        
        // Generate QRIS EMV Code (simplified format)
        $qrString = '00020101021226' . 
                   $merchantId . 
                   str_pad($pesanan->id_pesanan, 10, '0', STR_PAD_LEFT) .
                   str_pad((int)$amount, 15, '0', STR_PAD_LEFT);
        
        $pesanan->update([
            'metode_pembayaran' => 'qris',
            'status_pembayaran' => 'pending',
            'qr_code' => $qrString,
        ]);

        return [
            'success' => true,
            'message' => 'Silakan scan QR Code QRIS menggunakan aplikasi pembayaran Anda.',
            'payment_method' => 'qris',
            'qr_code' => $qrString,
            'qr_image' => $this->generateQRImage($qrString),
            'amount' => $pesanan->total_nota,
        ];
    }

    /**
     * Generate QR Code Image dari string QRIS
     */
    private function generateQRImage($data)
    {
        // Menggunakan API pihak ketiga untuk generate QR code
        // Menggunakan service qr-server.com (gratis dan tidak perlu library)
        $encoded = urlencode($data);
        $qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={$encoded}";
        
        return $qrImageUrl;
    }

    /**
     * Verifikasi pembayaran (untuk admin)
     */
    public function verifyPayment(Pesanan $pesanan, bool $isApproved)
    {
        if ($isApproved) {
            $pesanan->update([
                'status_pembayaran' => 'paid',
            ]);

            return [
                'success' => true,
                'message' => 'Pembayaran berhasil diverifikasi',
            ];
        } else {
            $pesanan->update([
                'status_pembayaran' => 'failed',
            ]);

            return [
                'success' => false,
                'message' => 'Pembayaran ditolak',
            ];
        }
    }

    /**
     * Get payment instructions
     */
    public function getPaymentInstructions($method, Pesanan $pesanan = null)
    {
        $instructions = [
            'transfer' => [
                'title' => 'Transfer Bank',
                'banks' => [
                    ['name' => 'BCA', 'account' => '1234567890', 'holder' => 'Apotek Sehat'],
                    ['name' => 'Mandiri', 'account' => '0987654321', 'holder' => 'Apotek Sehat'],
                    ['name' => 'BNI', 'account' => '5555666677', 'holder' => 'Apotek Sehat'],
                ],
                'instructions' => [
                    'Pilih bank tujuan transfer',
                    'Transfer sesuai nominal yang tertera',
                    'Simpan bukti transfer',
                    'Upload bukti transfer pada halaman pesanan',
                    'Tunggu verifikasi dari admin (1x24 jam)',
                ],
            ],
            'e-wallet' => [
                'title' => 'E-Wallet (GoPay, OVO, DANA, ShopeePay)',
                'instructions' => [
                    'Pilih aplikasi e-wallet yang Anda gunakan',
                    'Scan QR Code yang muncul',
                    'Atau masukkan nomor Virtual Account',
                    'Konfirmasi pembayaran di aplikasi',
                    'Pembayaran akan terverifikasi otomatis',
                ],
            ],
            'qris' => [
                'title' => 'QRIS',
                'instructions' => [
                    'Buka aplikasi mobile banking atau e-wallet Anda',
                    'Pilih menu Scan QR / QRIS',
                    'Scan QR Code yang ditampilkan',
                    'Periksa nominal pembayaran',
                    'Konfirmasi pembayaran',
                ],
            ],
        ];

        // Generate QR image for QRIS if pesanan is provided
        if ($method === 'qris' && $pesanan) {
            $qrData = '00020101021226' . 
                     '00000000000001' . 
                     str_pad($pesanan->id_pesanan, 10, '0', STR_PAD_LEFT) .
                     str_pad((int)$pesanan->total_nota, 15, '0', STR_PAD_LEFT);
            
            $instructions['qris']['qr_image'] = $this->generateQRImage($qrData);
        }

        return $instructions[$method] ?? null;
    }
}
