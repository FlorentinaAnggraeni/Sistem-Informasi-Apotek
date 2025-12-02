<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan semua seeder.
     */
    public function run(): void
    {
        // Panggil semua seeder dengan urutan yang benar
        $this->call([
            UserSeeder::class,
            PelangganSeeder::class,
            KaryawanSeeder::class,
            PemilikSeeder::class,
            KategoriSeeder::class,
            SupplierSeeder::class,
            ObatSeeder::class,
        ]);
    }
}
