<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resep;
use App\Models\Pelanggan;
use Carbon\Carbon;

class ResepSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil pelanggan pertama untuk sample
        $pelanggan = Pelanggan::first();
        
        if (!$pelanggan) {
            $this->command->warn('Tidak ada data pelanggan. Jalankan seeder pelanggan terlebih dahulu.');
            return;
        }

        $reseps = [
            [
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'no_resep' => 'RSP-' . date('Ymd') . '-001',
                'nama_dokter' => 'dr. Ahmad Hidayat, Sp.PD',
                'nama_pasien' => 'Budi Santoso',
                'diagnosa' => 'Hipertensi Grade 2',
                'foto_resep' => null,
                'status' => 'pending',
                'catatan_apoteker' => null,
                'id_apoteker' => null,
                'tanggal_resep' => Carbon::today(),
            ],
            [
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'no_resep' => 'RSP-' . date('Ymd') . '-002',
                'nama_dokter' => 'dr. Siti Nurhaliza, Sp.A',
                'nama_pasien' => 'Rina Wijaya',
                'diagnosa' => 'ISPA (Infeksi Saluran Pernapasan Atas)',
                'foto_resep' => null,
                'status' => 'pending',
                'catatan_apoteker' => null,
                'id_apoteker' => null,
                'tanggal_resep' => Carbon::today(),
            ],
            [
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'no_resep' => 'RSP-' . date('Ymd', strtotime('-1 day')) . '-001',
                'nama_dokter' => 'dr. Bambang Susilo, Sp.JP',
                'nama_pasien' => 'Dewi Lestari',
                'diagnosa' => 'Diabetes Mellitus Type 2',
                'foto_resep' => null,
                'status' => 'diproses',
                'catatan_apoteker' => 'Resep sedang disiapkan, obat tersedia',
                'id_apoteker' => null,
                'tanggal_resep' => Carbon::yesterday(),
            ],
            [
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'no_resep' => 'RSP-' . date('Ymd', strtotime('-2 days')) . '-001',
                'nama_dokter' => 'dr. Linda Kusuma, Sp.OG',
                'nama_pasien' => 'Ayu Kartika',
                'diagnosa' => 'Anemia Defisiensi Besi',
                'foto_resep' => null,
                'status' => 'selesai',
                'catatan_apoteker' => 'Resep telah dipenuhi, harap minum obat sesuai anjuran',
                'id_apoteker' => null,
                'tanggal_resep' => Carbon::now()->subDays(2),
            ],
            [
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'no_resep' => 'RSP-' . date('Ymd', strtotime('-3 days')) . '-001',
                'nama_dokter' => 'dr. Rudi Hartono, Sp.S',
                'nama_pasien' => 'Joko Purwanto',
                'diagnosa' => 'Migrain',
                'foto_resep' => null,
                'status' => 'ditolak',
                'catatan_apoteker' => 'Resep tidak lengkap, harap melengkapi stempel dokter',
                'id_apoteker' => null,
                'tanggal_resep' => Carbon::now()->subDays(3),
            ],
        ];

        foreach ($reseps as $resep) {
            Resep::create($resep);
        }

        $this->command->info('Sample resep data created successfully!');
    }
}
