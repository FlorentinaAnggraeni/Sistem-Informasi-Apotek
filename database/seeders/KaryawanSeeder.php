<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user dengan role karyawan atau apoteker
        $karyawanUsers = DB::table('users')->whereIn('role', ['karyawan', 'apoteker'])->get();

        foreach ($karyawanUsers as $user) {
            // Cek apakah sudah ada data karyawan untuk user ini
            $exists = DB::table('karyawans')->where('id_user', $user->id)->exists();
            
            if (!$exists) {
                DB::table('karyawans')->insert([
                    'id_user' => $user->id,
                    'nama_karyawan' => $user->name,
                    'alamat_karyawan' => $user->alamat,
                    'no_telp_karyawan' => $user->no_hp,
                    'jabatan' => $user->role === 'apoteker' ? 'Apoteker' : 'Staff',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
