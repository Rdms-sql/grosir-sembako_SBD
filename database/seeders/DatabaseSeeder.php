<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'nama_lengkap' => 'Admin Utama',
            'username' => 'admin_grosir',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->call([
            SupplierSeeder::class,
            KonsumenSeeder::class,
            BarangSeeder::class,
            PenjualanDummySeeder::class, // Seeder dummy untuk memenuhi FK constraint piutangs.id_penjualan
            PiutangSeeder::class,
            PenerimaanPiutangSeeder::class,
        ]);
    }
}
