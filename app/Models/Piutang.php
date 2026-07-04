<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Piutang extends Model
{
    protected $primaryKey = 'id_piutang';

    protected $fillable = [
        'id_konsumen',
        'id_penjualan',
        'total_piutang',
        'sisa_piutang',
        'jatuh_tempo',
        'status',
    ];

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'id_konsumen', 'id_konsumen');
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan');
    }

    public function penerimaanPiutangs()
    {
        return $this->hasMany(PenerimaanPiutang::class, 'id_piutang');
    }
}