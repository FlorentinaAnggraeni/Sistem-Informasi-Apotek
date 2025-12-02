<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Obat;
use App\Models\Kategori;
use App\Models\Supplier;

class ObatSeeder extends Seeder
{
    public function run(): void
    {
        $obats = [
            // Obat Bebas
            [
                'id_kategori' => 1,
                'id_supplier' => 1,
                'nama_obat' => 'Paracetamol 500mg',
                'jenis_obat' => 'Tablet',
                'deskripsi_obat' => 'Obat pereda nyeri dan penurun demam',
                'harga_obat' => 5000,
                'stok_obat' => 100,
                'tanggal_kadaluarsa' => '2026-12-31',
                'no_batch' => 'PARA-2024-001',
            ],
            [
                'id_kategori' => 1,
                'id_supplier' => 2,
                'nama_obat' => 'Antasida DOEN',
                'jenis_obat' => 'Tablet',
                'deskripsi_obat' => 'Obat maag untuk menetralkan asam lambung',
                'harga_obat' => 3000,
                'stok_obat' => 80,
                'tanggal_kadaluarsa' => '2026-06-30',
                'no_batch' => 'ANTA-2024-002',
            ],
            [
                'id_kategori' => 1,
                'id_supplier' => 1,
                'nama_obat' => 'Promag',
                'jenis_obat' => 'Tablet',
                'deskripsi_obat' => 'Obat untuk mengatasi gangguan lambung',
                'harga_obat' => 8000,
                'stok_obat' => 60,
                'tanggal_kadaluarsa' => '2026-09-15',
                'no_batch' => 'PROM-2024-003',
            ],
            
            // Obat Bebas Terbatas
            [
                'id_kategori' => 2,
                'id_supplier' => 2,
                'nama_obat' => 'Bodrex',
                'jenis_obat' => 'Tablet',
                'deskripsi_obat' => 'Obat sakit kepala dan demam',
                'harga_obat' => 4000,
                'stok_obat' => 90,
                'tanggal_kadaluarsa' => '2026-11-20',
                'no_batch' => 'BODR-2024-004',
            ],
            [
                'id_kategori' => 2,
                'id_supplier' => 3,
                'nama_obat' => 'OBH Combi',
                'jenis_obat' => 'Sirup',
                'deskripsi_obat' => 'Obat batuk berdahak',
                'harga_obat' => 15000,
                'stok_obat' => 50,
                'tanggal_kadaluarsa' => '2026-08-10',
                'no_batch' => 'OBH-2024-005',
            ],
            
            // Obat Keras
            [
                'id_kategori' => 3,
                'id_supplier' => 3,
                'nama_obat' => 'Amoxicillin 500mg',
                'jenis_obat' => 'Kapsul',
                'deskripsi_obat' => 'Antibiotik untuk infeksi bakteri',
                'harga_obat' => 12000,
                'stok_obat' => 70,
                'tanggal_kadaluarsa' => '2026-07-25',
                'no_batch' => 'AMOX-2024-006',
            ],
            [
                'id_kategori' => 3,
                'id_supplier' => 4,
                'nama_obat' => 'Ciprofloxacin 500mg',
                'jenis_obat' => 'Tablet',
                'deskripsi_obat' => 'Antibiotik spektrum luas',
                'harga_obat' => 18000,
                'stok_obat' => 40,
                'tanggal_kadaluarsa' => '2026-10-05',
                'no_batch' => 'CIPR-2024-007',
            ],
            
            // Vitamin & Suplemen
            [
                'id_kategori' => 4,
                'id_supplier' => 2,
                'nama_obat' => 'Vitamin C 1000mg',
                'jenis_obat' => 'Tablet',
                'deskripsi_obat' => 'Suplemen vitamin C untuk daya tahan tubuh',
                'harga_obat' => 25000,
                'stok_obat' => 120,
                'tanggal_kadaluarsa' => '2027-03-15',
                'no_batch' => 'VITC-2024-008',
            ],
            [
                'id_kategori' => 4,
                'id_supplier' => 2,
                'nama_obat' => 'Vitamin B Complex',
                'jenis_obat' => 'Tablet',
                'deskripsi_obat' => 'Suplemen vitamin B kompleks',
                'harga_obat' => 30000,
                'stok_obat' => 100,
                'tanggal_kadaluarsa' => '2027-01-20',
                'no_batch' => 'VITB-2024-009',
            ],
            [
                'id_kategori' => 4,
                'id_supplier' => 5,
                'nama_obat' => 'Blackmores Omega 3',
                'jenis_obat' => 'Kapsul',
                'deskripsi_obat' => 'Suplemen minyak ikan omega 3',
                'harga_obat' => 120000,
                'stok_obat' => 30,
                'tanggal_kadaluarsa' => '2026-12-31',
                'no_batch' => 'OMEG-2024-010',
            ],
            
            // Obat Tradisional
            [
                'id_kategori' => 5,
                'id_supplier' => 4,
                'nama_obat' => 'Tolak Angin',
                'jenis_obat' => 'Cair',
                'deskripsi_obat' => 'Obat herbal untuk masuk angin',
                'harga_obat' => 5000,
                'stok_obat' => 150,
                'tanggal_kadaluarsa' => '2026-06-30',
                'no_batch' => 'TOLA-2024-011',
            ],
            [
                'id_kategori' => 5,
                'id_supplier' => 4,
                'nama_obat' => 'Antangin JRG',
                'jenis_obat' => 'Cair',
                'deskripsi_obat' => 'Obat herbal dengan jahe royal jelly ginseng',
                'harga_obat' => 6000,
                'stok_obat' => 100,
                'tanggal_kadaluarsa' => '2026-09-15',
                'no_batch' => 'ANTA-2024-012',
            ],
            
            // Alat Kesehatan
            [
                'id_kategori' => 6,
                'id_supplier' => 5,
                'nama_obat' => 'Masker Medis 3 Ply',
                'jenis_obat' => 'Alat Kesehatan',
                'deskripsi_obat' => 'Masker medis 3 lapis isi 50 pcs',
                'harga_obat' => 45000,
                'stok_obat' => 200,
                'tanggal_kadaluarsa' => '2028-12-31',
                'no_batch' => 'MASK-2024-013',
            ],
            [
                'id_kategori' => 6,
                'id_supplier' => 5,
                'nama_obat' => 'Hand Sanitizer 100ml',
                'jenis_obat' => 'Alat Kesehatan',
                'deskripsi_obat' => 'Hand sanitizer dengan alkohol 70%',
                'harga_obat' => 15000,
                'stok_obat' => 180,
                'tanggal_kadaluarsa' => '2027-06-30',
                'no_batch' => 'HAND-2024-014',
            ],
            [
                'id_kategori' => 6,
                'id_supplier' => 3,
                'nama_obat' => 'Thermometer Digital',
                'jenis_obat' => 'Alat Kesehatan',
                'deskripsi_obat' => 'Thermometer digital untuk mengukur suhu tubuh',
                'harga_obat' => 35000,
                'stok_obat' => 50,
                'tanggal_kadaluarsa' => null,
                'no_batch' => 'THER-2024-015',
            ],
        ];

        foreach ($obats as $obat) {
            Obat::create($obat);
        }
    }
}
