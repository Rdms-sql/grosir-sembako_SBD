<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerimaanPiutang extends Model
{
    protected $primaryKey = 'id_terima_piutang';

    protected $fillable = [
        'id_piutang',
        'id_user',
        'tgl_terima',
        'jumlah_terima',
        'metode_bayar',
    ];

    public function piutang()
    {
        return $this->belongsTo(Piutang::class, 'id_piutang', 'id_piutang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    protected static function booted(): void
    {
        static::created(function (PenerimaanPiutang $penerimaan) {
            $penerimaan->piutang?->updateSisaPiutang();
        });

        // Kalau ada koreksi/hapus data penerimaan, sisa_piutang juga harus disesuaikan.
        static::updated(function (PenerimaanPiutang $penerimaan) {
            $penerimaan->piutang?->updateSisaPiutang();
        });

        static::deleted(function (PenerimaanPiutang $penerimaan) {
            $penerimaan->piutang?->updateSisaPiutang();
        });
    }
}
