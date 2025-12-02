<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailResep extends Model
{
    protected $table = 'detail_reseps';
    protected $primaryKey = 'id_detail_resep';

    protected $fillable = [
        'id_resep',
        'id_obat',
        'jumlah',
        'aturan_pakai',
        'catatan',
    ];

    public function resep()
    {
        return $this->belongsTo(Resep::class, 'id_resep', 'id_resep');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat', 'id_obat');
    }
}
