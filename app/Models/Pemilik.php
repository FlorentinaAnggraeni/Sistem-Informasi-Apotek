<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemilik extends Model
{
    use HasFactory;

    protected $table = 'pemiliks';
    protected $primaryKey = 'id_pemilik';

    protected $fillable = [
        'id_user',
        'nama_pemilik',
        'no_telp_pemilik',
        'email_pemilik',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
