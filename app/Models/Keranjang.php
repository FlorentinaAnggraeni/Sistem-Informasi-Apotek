<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    protected $table = 'keranjangs';
    protected $primaryKey = 'id_keranjang';

    protected $fillable = [
        'id_pelanggan',
        'id_obat',
        'jumlah',
    ];

    protected $casts = [
        'jumlah' => 'integer',
    ];

    // Relasi ke Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Relasi ke Obat
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat', 'id_obat');
    }

    // Accessor untuk subtotal
    public function getSubtotalAttribute()
    {
        return $this->jumlah * ($this->obat->harga_obat ?? 0);
    }

    // Get subtotal untuk item ini (backward compatibility)
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    // Update jumlah
    public function updateQuantity($jumlah)
    {
        // Cek stok obat
        if ($this->obat->hasStock($jumlah)) {
            $this->jumlah = $jumlah;
            return $this->save();
        }
        return false;
    }
    
    // Increment jumlah
    public function incrementQuantity($amount = 1)
    {
        return $this->updateQuantity($this->jumlah + $amount);
    }
    
    // Decrement jumlah
    public function decrementQuantity($amount = 1)
    {
        $newAmount = max(1, $this->jumlah - $amount);
        return $this->updateQuantity($newAmount);
    }
}
