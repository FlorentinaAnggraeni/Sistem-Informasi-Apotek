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
        // Panggil UserSeeder
        $this->call([
            UserSeeder::class,
        ]);
    }
}
