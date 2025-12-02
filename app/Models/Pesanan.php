<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanans';
    protected $primaryKey = 'id_pesanan';

    protected $fillable = [
        'id_pelanggan',
        'id_karyawan',
        'total_nota',
        'metode_pembayaran',
        'payment_code',
        'va_number',
        'qr_code',
        'bukti_pembayaran',
        'status_pembayaran',
        'status_pengiriman',
        'status_penerimaan',
        'alamat_pengiriman',
        'catatan',
        'tanggal_pengiriman',
        'no_resi',
    ];

    protected $casts = [
        'total_nota' => 'integer',
        'tanggal_pengiriman' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Accessors
    public function getKodePesananAttribute()
    {
        return 'ORD-' . str_pad($this->id_pesanan, 6, '0', STR_PAD_LEFT);
    }

    public function getTanggalPesananAttribute()
    {
        return $this->created_at;
    }

    public function getStatusPesananAttribute()
    {
        // Map status_pengiriman to status_pesanan for backward compatibility
        return $this->status_pengiriman;
    }

    // Relasi ke Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Relasi ke Karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }

    // Relasi ke DetailPesanan
    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }

    // Scope untuk filter berdasarkan status
    public function scopePending($query)
    {
        return $query->where('status_pembayaran', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status_pembayaran', 'paid');
    }

    public function scopeShipped($query)
    {
        return $query->where('status_pengiriman', 'shipped');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status_pengiriman', 'delivered');
    }

    // Hitung total dari detail pesanan
    public function calculateTotal()
    {
        $total = $this->detailPesanans()->sum(function($detail) {
            return $detail->jumlah * $detail->harga_satuan;
        });
        
        $this->total_nota = $total;
        $this->save();
        
        return $total;
    }

    // Update status pembayaran
    public function markAsPaid()
    {
        $this->status_pembayaran = 'paid';
        return $this->save();
    }

    // Update status pengiriman
    public function markAsShipped($noResi = null)
    {
        $this->status_pengiriman = 'shipped';
        $this->tanggal_pengiriman = now();
        if ($noResi) {
            $this->no_resi = $noResi;
        }
        return $this->save();
    }

    public function markAsDelivered()
    {
        $this->status_pengiriman = 'delivered';
        $this->status_penerimaan = 'diterima';
        return $this->save();
    }
}
