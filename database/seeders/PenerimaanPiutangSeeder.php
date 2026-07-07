<?php

namespace Database\Seeders;

use App\Models\Piutang;
use App\Models\PenerimaanPiutang;
use App\Models\User;
use Illuminate\Database\Seeder;

class PenerimaanPiutangSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::first()->id_user;
        $piutangs = Piutang::orderBy('id_piutang')->get();

        if ($piutangs->count() < 4) {
            $this->command->warn('Piutang kurang dari 4 baris — sebagian skenario PenerimaanPiutang dilewati.');
            return;
        }

        // Skenario 1: cicilan sebagian
        $penerimaan = PenerimaanPiutang::create([
            'id_piutang' => $piutangs[0]->id_piutang,
            'id_user' => $userId,
            'tgl_terima' => now()->subDays(10),
            'jumlah_terima' => (float) $piutangs[0]->total_piutang * 0.4,
            'metode_bayar' => 'tunai',
        ]);
        $piutangs[0]->fresh()->updateSisaPiutang();

        // Skenario 2: lunas satu kali bayar penuh
        $penerimaan = PenerimaanPiutang::create([
            'id_piutang' => $piutangs[1]->id_piutang,
            'id_user' => $userId,
            'tgl_terima' => now()->subDays(5),
            'jumlah_terima' => $piutangs[1]->total_piutang,
            'metode_bayar' => 'transfer',
        ]);
        $piutangs[1]->fresh()->updateSisaPiutang();

        // Skenario 3: lunas lewat 2 kali cicilan
        // PENTING: refresh $piutangs[2] sebelum create kedua, supaya
        // instance yang dipakai untuk keperluan lain di seeder ini
        // tidak stale — bukan fix untuk booted(), tapi kebersihan kode.
        PenerimaanPiutang::create([
            'id_piutang' => $piutangs[2]->id_piutang,
            'id_user' => $userId,
            'tgl_terima' => now()->subDays(8),
            'jumlah_terima' => (float) $piutangs[2]->total_piutang * 0.5,
            'metode_bayar' => 'tunai',
        ]);
        $piutangs[2]->fresh()->updateSisaPiutang();

        PenerimaanPiutang::create([
            'id_piutang' => $piutangs[2]->id_piutang,
            'id_user' => $userId,
            'tgl_terima' => now()->subDays(2),
            'jumlah_terima' => (float) $piutangs[2]->total_piutang * 0.5,
            'metode_bayar' => 'transfer',
        ]);
        $piutangs[2]->fresh()->updateSisaPiutang();

        // Skenario 4: sengaja tidak dibuatkan PenerimaanPiutang
    }
}
