<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranHutang extends Model
{
    protected $primaryKey = 'id_pembayaran_hutang';

    protected $fillable = [
        'id_hutang',
        'id_user',
        'tgl_bayar',
        'jumlah_bayar',
        'metode_bayar',
    ];

    public function hutang()
    {
        return $this->belongsTo(Hutang::class, 'id_hutang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}