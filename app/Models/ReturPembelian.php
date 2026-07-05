<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturPembelian extends Model
{
    protected $primaryKey = 'id_retur_beli';

    protected $fillable = [
        'id_pembelian',
        'id_user',
        'tgl_retur',
        'total_retur',
        'keterangan',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function detailReturPembelians()
    {
        return $this->hasMany(DetailReturPembelian::class, 'id_retur_beli');
    }
}