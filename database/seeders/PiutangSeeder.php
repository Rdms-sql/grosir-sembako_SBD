<?php

namespace Database\Seeders;

use App\Models\Penjualan;
use App\Models\Piutang;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PiutangSeeder extends Seeder
{
    public function run(): void
    {
        $penjualanCredits = Penjualan::where('status_bayar', 'credit')->get();

        foreach ($penjualanCredits as $penjualan) {
            Piutang::create([
                'id_penjualan' => $penjualan->id_penjualan,
                'id_konsumen' => $penjualan->id_konsumen,
                'total_piutang' => $penjualan->total_jual,
                'sisa_piutang' => $penjualan->total_jual, // awal: belum ada pembayaran
                'jatuh_tempo' => Carbon::parse($penjualan->tgl_penjualan)->addDays(30),
                'status' => 'belum_lunas',
            ]);
        }
    }
}
