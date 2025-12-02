<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'nama_supplier' => 'PT. Kimia Farma',
                'alamat_supplier' => 'Jl. Veteran No. 9, Jakarta Pusat',
                'no_telp_supplier' => '021-3841031',
                'email_supplier' => 'info@kimiafarma.co.id',
                'kontak_person' => 'Budi Santoso',
            ],
            [
                'nama_supplier' => 'PT. Kalbe Farma',
                'alamat_supplier' => 'Jl. Let. Jend. Suprapto, Cempaka Putih, Jakarta Pusat',
                'no_telp_supplier' => '021-4212808',
                'email_supplier' => 'customer.care@kalbe.co.id',
                'kontak_person' => 'Siti Aminah',
            ],
            [
                'nama_supplier' => 'PT. Dexa Medica',
                'alamat_supplier' => 'Jl. Bambang Utoyo No. 138, Palembang',
                'no_telp_supplier' => '0711-411511',
                'email_supplier' => 'info@dexa-medica.com',
                'kontak_person' => 'Ahmad Fauzi',
            ],
            [
                'nama_supplier' => 'PT. Sanbe Farma',
                'alamat_supplier' => 'Jl. Tamansari No. 80, Bandung',
                'no_telp_supplier' => '022-2500456',
                'email_supplier' => 'info@sanbe.co.id',
                'kontak_person' => 'Dewi Lestari',
            ],
            [
                'nama_supplier' => 'PT. Indofarma',
                'alamat_supplier' => 'Jl. Indofarma No. 1, Bekasi',
                'no_telp_supplier' => '021-8810942',
                'email_supplier' => 'sekretariat@indofarma.id',
                'kontak_person' => 'Rina Wijaya',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
