<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obats';
    protected $primaryKey = 'id_obat';

    protected $fillable = [
        'nama_obat',
        'jenis_obat',
        'deskripsi_obat',
        'harga_obat',
        'stok_obat',
        'id_kategori',
        'id_supplier',
        'gambar_obat',
        'tanggal_kadaluarsa',
        'no_batch',
    ];

    protected $casts = [
        'harga_obat' => 'decimal:2',
        'stok_obat' => 'integer',
        'tanggal_kadaluarsa' => 'date',
    ];

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    // Relasi ke Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id_supplier');
    }

    // Relasi ke DetailPesanan
    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class, 'id_obat', 'id_obat');
    }

    // Relasi ke Keranjang
    public function keranjangs()
    {
        return $this->hasMany(Keranjang::class, 'id_obat', 'id_obat');
    }

    // Scope untuk obat yang masih tersedia
    public function scopeAvailable($query)
    {
        return $query->where('stok_obat', '>', 0);
    }

    // Scope untuk obat yang hampir habis
    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('stok_obat', '<=', $threshold)->where('stok_obat', '>', 0);
    }

    // Check apakah stok cukup
    public function hasStock($quantity)
    {
        return $this->stok_obat >= $quantity;
    }

    // Kurangi stok
    public function reduceStock($quantity)
    {
        if ($this->hasStock($quantity)) {
            $this->stok_obat -= $quantity;
            return $this->save();
        }
        return false;
    }

    // Tambah stok
    public function addStock($quantity)
    {
        $this->stok_obat += $quantity;
        return $this->save();
    }
}
