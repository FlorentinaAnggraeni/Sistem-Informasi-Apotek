<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawans';
    protected $primaryKey = 'id_karyawan';

    protected $fillable = [
        'id_user',
        'nama_karyawan',
        'jabatan',
        'no_telp_karyawan',
        'alamat_karyawan',
        'tanggal_bergabung',
        'gaji',
    ];

    protected $casts = [
        'tanggal_bergabung' => 'date',
        'gaji' => 'decimal:2',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // Relasi ke Pesanan (karyawan yang memproses)
    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'id_karyawan', 'id_karyawan');
    }

    // Relasi ke Transaksi
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_karyawan', 'id_karyawan');
    }

    // Get total pesanan yang diproses
    public function totalPesananDiproses()
    {
        return $this->pesanans()->count();
    }
}
