<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk tabel users
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name'       => 'Denis',
                'username'   => 'denis.login',
                'email'      => 'denis@mail.com',
                'password'   => Hash::make('denP'),
                'alamat'     => 'Paingan',
                'no_hp'      => '0812345678',
                'role'       => 'pelanggan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Andi',
                'username'   => 'andi',
                'email'      => 'pemilik@gmail.com',
                'password'   => Hash::make('password123'),
                'alamat'     => 'Sleman',
                'no_hp'      => '0856756799678',
                'role'       => 'pemilik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Budi',
                'username'   => 'budi.kr',
                'email'      => 'karyawan@example.com',
                'password'   => Hash::make('password123'),
                'alamat'     => 'Sleman',
                'no_hp'      => '0854265890',
                'role'       => 'karyawan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
