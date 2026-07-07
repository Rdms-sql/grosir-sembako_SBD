<?php

namespace Database\Seeders;

use App\Models\Konsumen;
use App\Models\Penjualan;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * DUMMY SEEDER — bukan seeder resmi modul Penjualan.
 * Dibuat oleh Kelompok 4 hanya untuk memenuhi FK constraint
 * piutangs.id_penjualan saat testing modul Piutang secara independen.
 * Hapus/nonaktifkan panggilan ini di DatabaseSeeder begitu
 * Kelompok Penjualan sudah punya seeder resmi mereka sendiri.
 */
class PenjualanDummySeeder extends Seeder
{
    public function run(): void
    {
        $konsumenIds = Konsumen::pluck('id_konsumen')->toArray();
        $userId = User::first()->id_user;

        $data = [
            ['status_bayar' => 'credit', 'total_jual' => 500000, 'tgl_penjualan' => '2026-05-01'],
            ['status_bayar' => 'credit', 'total_jual' => 750000, 'tgl_penjualan' => '2026-05-10'],
            ['status_bayar' => 'credit', 'total_jual' => 300000, 'tgl_penjualan' => '2026-05-15'],
            ['status_bayar' => 'cash', 'total_jual' => 200000, 'tgl_penjualan' => '2026-05-20'],
            ['status_bayar' => 'credit', 'total_jual' => 1200000, 'tgl_penjualan' => '2026-05-25'],
        ];

        foreach ($data as $row) {
            Penjualan::create([
                'id_konsumen' => $konsumenIds[array_rand($konsumenIds)],
                'id_user' => $userId,
                'id_pesan_konsumen' => null,
                'tgl_penjualan' => $row['tgl_penjualan'],
                'status_bayar' => $row['status_bayar'],
                'total_jual' => $row['total_jual'],
            ]);
        }
    }
}
