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
        return $this->belongsTo(Piutang::class, 'id_piutang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}