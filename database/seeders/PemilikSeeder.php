<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PemilikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user dengan role pemilik
        $pemilikUsers = DB::table('users')->where('role', 'pemilik')->get();

        foreach ($pemilikUsers as $user) {
            // Cek apakah sudah ada data pemilik untuk user ini
            $exists = DB::table('pemiliks')->where('email', $user->email)->exists();
            
            if (!$exists) {
                DB::table('pemiliks')->insert([
                    'nama' => $user->name,
                    'email' => $user->email,
                    'no_telpon' => $user->no_hp,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
