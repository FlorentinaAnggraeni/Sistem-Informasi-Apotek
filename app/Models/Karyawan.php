<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obat';

    protected $fillable = [
        'kode_obat',
        'nama_obat',
        'kategori',
        'jenis',
        'satuan',
        'harga_beli',
        'harga_jual',
        'stok',
        'stok_minimum',
        'tanggal_kadaluarsa',
        'supplier',
        'deskripsi',
        'foto',
        'status'
    ];

    protected $casts = [
        'tanggal_kadaluarsa' => 'date',
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'stok' => 'integer',
        'stok_minimum' => 'integer'
    ];

    // Accessor untuk format harga beli
    public function getHargaBeliFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga_beli, 0, ',', '.');
    }

    // Accessor untuk format harga jual
    public function getHargaJualFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga_jual, 0, ',', '.');
    }

    // Cek apakah stok menipis
    public function isStokMenipis()
    {
        return $this->stok <= $this->stok_minimum;
    }

    // Cek apakah sudah kadaluarsa
    public function isKadaluarsa()
    {
        return $this->tanggal_kadaluarsa && $this->tanggal_kadaluarsa->isPast();
    }

    // Cek apakah mendekati kadaluarsa (30 hari)
    public function isMendekatiKadaluarsa()
    {
        if (!$this->tanggal_kadaluarsa) return false;
        return $this->tanggal_kadaluarsa->diffInDays(now()) <= 30 && !$this->isKadaluarsa();
    }

    // Scope untuk obat aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope untuk obat stok menipis
    public function scopeStokMenipis($query)
    {
        return $query->whereRaw('stok <= stok_minimum');
    }

    // Scope untuk obat mendekati kadaluarsa
    public function scopeMendekatiKadaluarsa($query)
    {
        return $query->whereNotNull('tanggal_kadaluarsa')
                    ->whereDate('tanggal_kadaluarsa', '>', now())
                    ->whereDate('tanggal_kadaluarsa', '<=', now()->addDays(30));
    }

    // Scope untuk obat kadaluarsa
    public function scopeKadaluarsa($query)
    {
        return $query->whereNotNull('tanggal_kadaluarsa')
                    ->whereDate('tanggal_kadaluarsa', '<=', now());
    }

    // Relasi dengan detail pesanan
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'obat_id');
    }
}
