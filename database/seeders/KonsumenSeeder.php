<?php

namespace Database\Seeders;

use App\Models\Konsumen;
use Illuminate\Database\Seeder;

class KonsumenSeeder extends Seeder
{
    public function run(): void
    {
        $konsumens = [
            ['nama_konsumen' => 'Toko Sari Rasa', 'no_hp' => '082345678901', 'alamat' => 'Jl. Kopo No. 10, Bandung', 'limit_kredit' => 5000000],
            ['nama_konsumen' => 'Warung Bu Yeni', 'no_hp' => '082345678902', 'alamat' => 'Jl. Buah Batu No. 25, Bandung', 'limit_kredit' => 2000000],
            ['nama_konsumen' => 'Toko Kelontong Makmur', 'no_hp' => '082345678903', 'alamat' => 'Jl. Ahmad Yani No. 33, Bandung', 'limit_kredit' => 3000000],
            ['nama_konsumen' => 'Minimarket Berkah', 'no_hp' => '082345678904', 'alamat' => 'Jl. Pasteur No. 51, Bandung', 'limit_kredit' => 10000000],
            ['nama_konsumen' => 'Toko Bahagia', 'no_hp' => '082345678905', 'alamat' => 'Jl. Setiabudi No. 8, Bandung', 'limit_kredit' => 1500000],
        ];

        foreach ($konsumens as $konsumen) {
            Konsumen::create($konsumen);
        }
    }
}
