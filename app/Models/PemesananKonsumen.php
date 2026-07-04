<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananKonsumen extends Model
{
    protected $table = 'pemesanan_konsumens';
    protected $primaryKey = 'id_pesan_konsumen';

    protected $fillable = [
        'id_konsumen',
        'id_user',
        'tgl_pesan',
        'status',
        'total_pesan',
    ];

    // ===== RELASI =====

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'id_konsumen');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function detailPesanKonsumens()
    {
        return $this->hasMany(DetailPesanKonsumen::class, 'id_pesan_konsumen');
    }

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class, 'id_pesan_konsumen');
    }
}
