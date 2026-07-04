<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanKonsumen extends Model
{
    protected $table = 'detail_pesan_konsumens';

    protected $fillable = [
        'id_pesan_konsumen',
        'id_barang',
        'jumlah_pesan',
        'subtotal',
    ];

    // ===== RELASI =====

    public function pemesananKonsumen()
    {
        return $this->belongsTo(PemesananKonsumen::class, 'id_pesan_konsumen');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
