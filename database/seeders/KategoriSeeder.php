<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            [
                'nama_kategori' => 'Obat Bebas',
                'deskripsi_kategori' => 'Obat yang dapat dibeli tanpa resep dokter',
            ],
            [
                'nama_kategori' => 'Obat Bebas Terbatas',
                'deskripsi_kategori' => 'Obat yang dapat dibeli tanpa resep dengan peringatan khusus',
            ],
            [
                'nama_kategori' => 'Obat Keras',
                'deskripsi_kategori' => 'Obat yang hanya dapat dibeli dengan resep dokter',
            ],
            [
                'nama_kategori' => 'Vitamin & Suplemen',
                'deskripsi_kategori' => 'Vitamin dan suplemen kesehatan',
            ],
            [
                'nama_kategori' => 'Obat Tradisional',
                'deskripsi_kategori' => 'Obat herbal dan tradisional',
            ],
            [
                'nama_kategori' => 'Alat Kesehatan',
                'deskripsi_kategori' => 'Alat-alat kesehatan dan medis',
            ],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}
