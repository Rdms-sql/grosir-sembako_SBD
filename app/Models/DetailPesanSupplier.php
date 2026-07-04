<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanSupplier extends Model
{
    protected $table = 'detail_pesan_suppliers';

    protected $fillable = [
        'id_pesan_supplier',
        'id_barang',
        'jumlah_pesan',
        'subtotal',
    ];

    // ===== RELASI =====

    public function pemesananSupplier()
    {
        return $this->belongsTo(PemesananSupplier::class, 'id_pesan_supplier');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
