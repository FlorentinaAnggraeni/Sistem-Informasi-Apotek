<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';
    protected $primaryKey = 'id_supplier';

    protected $fillable = [
        'nama_supplier',
        'alamat_supplier',
        'no_telp_supplier',
        'email_supplier',
        'kontak_person',
    ];

    // Relasi ke Obat
    public function obats()
    {
        return $this->hasMany(Obat::class, 'id_supplier', 'id_supplier');
    }

    // Get jumlah obat dari supplier
    public function jumlahObat()
    {
        return $this->obats()->count();
    }
}
