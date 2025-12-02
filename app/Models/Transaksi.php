<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_pesanan',
        'id_karyawan',
        'tanggal_transaksi',
        'total_transaksi',
        'metode_pembayaran',
        'status_transaksi',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'total_transaksi' => 'decimal:2',
    ];

    // Relasi ke Pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    // Relasi ke Karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }

    // Scope untuk filter berdasarkan status
    public function scopeSuccess($query)
    {
        return $query->where('status_transaksi', 'success');
    }

    public function scopePending($query)
    {
        return $query->where('status_transaksi', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status_transaksi', 'failed');
    }

    // Scope untuk filter berdasarkan tanggal
    public function scopeToday($query)
    {
        return $query->whereDate('tanggal_transaksi', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('tanggal_transaksi', now()->month)
                    ->whereYear('tanggal_transaksi', now()->year);
    }
}
