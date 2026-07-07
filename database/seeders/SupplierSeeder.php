<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['nama_supplier' => 'PT Sumber Makmur Sejahtera', 'no_hp' => '081234567801', 'alamat' => 'Jl. Raya Industri No. 12, Bandung'],
            ['nama_supplier' => 'CV Berkah Pangan Nusantara', 'no_hp' => '081234567802', 'alamat' => 'Jl. Soekarno Hatta No. 45, Bandung'],
            ['nama_supplier' => 'UD Tani Jaya Abadi', 'no_hp' => '081234567803', 'alamat' => 'Jl. Cihampelas No. 88, Bandung'],
            ['nama_supplier' => 'PT Grosir Sembako Indonesia', 'no_hp' => '081234567804', 'alamat' => 'Jl. Asia Afrika No. 23, Bandung'],
            ['nama_supplier' => 'CV Mitra Beras Sejahtera', 'no_hp' => '081234567805', 'alamat' => 'Jl. Dago No. 67, Bandung'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
