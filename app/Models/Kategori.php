<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris';
    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'nama_kategori',
        'deskripsi_kategori',
    ];

    // Relasi ke Obat
    public function obats()
    {
        return $this->hasMany(Obat::class, 'id_kategori', 'id_kategori');
    }

    // Get jumlah obat dalam kategori
    public function jumlahObat()
    {
        return $this->obats()->count();
    }
}
