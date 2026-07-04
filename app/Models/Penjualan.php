<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $primaryKey = 'id_penjualan';

    protected $fillable = [
        'id_konsumen',
        'id_user',
        'id_pesan_konsumen',
        'tgl_penjualan',
        'total_jual',
        'status_bayar',
    ];

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'id_konsumen', 'id_konsumen');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function pemesananKonsumen()
    {
        return $this->belongsTo(PemesananKonsumen::class, 'id_pesan_konsumen', 'id_pesan_konsumen');
    }

    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan');
    }

    public function piutang()
    {
        return $this->hasOne(Piutang::class, 'id_penjualan');
    }
}