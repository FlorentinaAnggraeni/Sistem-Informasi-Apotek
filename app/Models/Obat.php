<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obats'; // pastikan nama tabel sama
    protected $fillable = ['kode_obat', 'nama_obat', 'kategori', 'stok', 'harga', 'tanggal_expired'];
}
