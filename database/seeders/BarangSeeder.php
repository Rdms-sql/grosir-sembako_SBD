<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $supplierIds = Supplier::pluck('id_supplier')->toArray();

        $barangs = [
            ['nama_barang' => 'Beras Premium 5kg', 'harga_beli' => 55000, 'harga_jual' => 65000, 'satuan' => 'karung', 'stok' => 100],
            ['nama_barang' => 'Minyak Goreng 1L', 'harga_beli' => 14000, 'harga_jual' => 17000, 'satuan' => 'botol', 'stok' => 200],
            ['nama_barang' => 'Gula Pasir 1kg', 'harga_beli' => 12000, 'harga_jual' => 14500, 'satuan' => 'kg', 'stok' => 150],
            ['nama_barang' => 'Tepung Terigu 1kg', 'harga_beli' => 9000, 'harga_jual' => 11000, 'satuan' => 'kg', 'stok' => 120],
            ['nama_barang' => 'Telur Ayam 1kg', 'harga_beli' => 26000, 'harga_jual' => 29000, 'satuan' => 'kg', 'stok' => 80],
            ['nama_barang' => 'Mie Instan Dus', 'harga_beli' => 85000, 'harga_jual' => 95000, 'satuan' => 'dus', 'stok' => 50],
            ['nama_barang' => 'Kecap Manis 600ml', 'harga_beli' => 13000, 'harga_jual' => 16000, 'satuan' => 'botol', 'stok' => 90],
            ['nama_barang' => 'Garam Dapur 500g', 'harga_beli' => 3000, 'harga_jual' => 4500, 'satuan' => 'bungkus', 'stok' => 200],
        ];

        foreach ($barangs as $barang) {
            $barang['id_supplier'] = $supplierIds[array_rand($supplierIds)];
            Barang::create($barang);
        }
    }
}
