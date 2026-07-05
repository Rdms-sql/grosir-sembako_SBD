<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailReturPenjualan extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_retur_jual',
        'id_barang',
        'jumlah_retur',
        'subtotal',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}