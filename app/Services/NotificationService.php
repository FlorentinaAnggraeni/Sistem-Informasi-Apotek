<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Kirim notifikasi ke user
     */
    public function send($userId, $title, $message, $type = 'info', $link = null)
    {
        return Notification::create([
            'id_user' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'link' => $link,
            'is_read' => false,
        ]);
    }

    /**
     * Notifikasi pesanan baru untuk pelanggan
     */
    public function pesananDibuat($userId, $pesanan)
    {
        return $this->send(
            $userId,
            'Pesanan Berhasil Dibuat',
            "Pesanan #{$pesanan->id_pesanan} berhasil dibuat dengan total Rp " . number_format($pesanan->total_nota, 0, ',', '.'),
            'success',
            route('pelanggan.pesanan.show', $pesanan->id_pesanan)
        );
    }

    /**
     * Notifikasi pembayaran diterima
     */
    public function pembayaranDiterima($userId, $pesanan)
    {
        return $this->send(
            $userId,
            'Pembayaran Diterima',
            "Pembayaran untuk pesanan #{$pesanan->id_pesanan} telah diterima dan diverifikasi.",
            'success',
            route('pelanggan.pesanan.show', $pesanan->id_pesanan)
        );
    }

    /**
     * Notifikasi pesanan sedang diproses
     */
    public function pesananDiproses($userId, $pesanan)
    {
        return $this->send(
            $userId,
            'Pesanan Sedang Diproses',
            "Pesanan #{$pesanan->id_pesanan} sedang diproses dan akan segera dikirim.",
            'info',
            route('pelanggan.pesanan.show', $pesanan->id_pesanan)
        );
    }

    /**
     * Notifikasi pesanan dikirim
     */
    public function pesananDikirim($userId, $pesanan)
    {
        $message = "Pesanan #{$pesanan->id_pesanan} telah dikirim.";
        if ($pesanan->no_resi) {
            $message .= " No. Resi: {$pesanan->no_resi}";
        }

        return $this->send(
            $userId,
            'Pesanan Dikirim',
            $message,
            'info',
            route('pelanggan.pesanan.show', $pesanan->id_pesanan)
        );
    }

    /**
     * Notifikasi pesanan sampai
     */
    public function pesananSampai($userId, $pesanan)
    {
        return $this->send(
            $userId,
            'Pesanan Telah Sampai',
            "Pesanan #{$pesanan->id_pesanan} telah sampai di tujuan. Jangan lupa konfirmasi penerimaan.",
            'success',
            route('pelanggan.pesanan.show', $pesanan->id_pesanan)
        );
    }

    /**
     * Notifikasi stok obat menipis (untuk apoteker)
     */
    public function stokMenipis($userId, $obat)
    {
        return $this->send(
            $userId,
            'Stok Obat Menipis',
            "Stok {$obat->nama_obat} tinggal {$obat->stok_obat} unit. Segera lakukan restock.",
            'warning',
            route('apoteker.stok')
        );
    }

    /**
     * Notifikasi obat hampir kadaluarsa
     */
    public function obatHampirKadaluarsa($userId, $obat)
    {
        return $this->send(
            $userId,
            'Obat Hampir Kadaluarsa',
            "Obat {$obat->nama_obat} akan kadaluarsa pada {$obat->tanggal_kadaluarsa->format('d/m/Y')}.",
            'warning',
            route('apoteker.obat.show', $obat->id_obat)
        );
    }

    /**
     * Notifikasi pesanan baru untuk karyawan
     */
    public function pesananBaruMasuk($userId, $pesanan)
    {
        return $this->send(
            $userId,
            'Pesanan Baru Masuk',
            "Pesanan baru #{$pesanan->id_pesanan} menunggu untuk diproses.",
            'info',
            route('karyawan.pesanan.show', $pesanan->id_pesanan)
        );
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount($userId)
    {
        return Notification::forUser($userId)->unread()->count();
    }

    /**
     * Get notifications for user
     */
    public function getNotifications($userId, $limit = 10)
    {
        return Notification::forUser($userId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            return $notification->markAsRead();
        }
        return false;
    }

    /**
     * Mark all notifications as read for user
     */
    public function markAllAsRead($userId)
    {
        return Notification::forUser($userId)
            ->unread()
            ->update(['is_read' => true]);
    }
}
