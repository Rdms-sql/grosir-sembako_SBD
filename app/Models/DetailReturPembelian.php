<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailReturPembelian extends Model
{
    protected $table = 'detail_retur_pembelians';

    protected $fillable = [
        'id_retur_beli',
        'id_barang',
        'jumlah_retur',
        'subtotal',
    ];

    // ===== RELASI =====

    public function returPembelian()
    {
        return $this->belongsTo(ReturPembelian::class, 'id_retur_beli');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
