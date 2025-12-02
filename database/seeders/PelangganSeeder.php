<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user dengan role pelanggan
        $pelangganUsers = DB::table('users')->where('role', 'pelanggan')->get();

        foreach ($pelangganUsers as $user) {
            // Cek apakah sudah ada data pelanggan untuk user ini
            $exists = DB::table('pelanggans')->where('id_user', $user->id)->exists();
            
            if (!$exists) {
                DB::table('pelanggans')->insert([
                    'id_user' => $user->id,
                    'nama_pelanggan' => $user->name,
                    'alamat_pelanggan' => $user->alamat,
                    'no_telp_pelanggan' => $user->no_hp,
                    'email_pelanggan' => $user->email,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
