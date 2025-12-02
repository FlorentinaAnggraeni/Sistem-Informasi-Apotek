<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggans';
    protected $primaryKey = 'id_pelanggan';

    protected $fillable = [
        'id_user',
        'nama_pelanggan',
        'alamat_pelanggan',
        'no_telp_pelanggan',
        'email_pelanggan',
        'tanggal_lahir',
        'jenis_kelamin',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // Relasi ke Pesanan
    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Relasi ke Keranjang
    public function keranjangs()
    {
        return $this->hasMany(Keranjang::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Get total pesanan
    public function totalPesanan()
    {
        return $this->pesanans()->count();
    }

    // Get total belanja
    public function totalBelanja()
    {
        return $this->pesanans()->where('status_pembayaran', 'paid')->sum('total_nota');
    }
}
