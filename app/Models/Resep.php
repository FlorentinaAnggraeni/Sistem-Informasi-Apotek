<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $table = 'reseps';
    protected $primaryKey = 'id_resep';

    protected $fillable = [
        'id_pelanggan',
        'id_apoteker',
        'no_resep',
        'nama_dokter',
        'nama_pasien',
        'diagnosa',
        'foto_resep',
        'status',
        'catatan_apoteker',
        'tanggal_resep',
    ];

    protected $casts = [
        'tanggal_resep' => 'date',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function apoteker()
    {
        return $this->belongsTo(Karyawan::class, 'id_apoteker', 'id_karyawan');
    }

    public function detailReseps()
    {
        return $this->hasMany(DetailResep::class, 'id_resep', 'id_resep');
    }
}
