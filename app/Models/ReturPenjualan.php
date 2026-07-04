<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturPenjualan extends Model
{
    protected $table = 'retur_penjualans';
    protected $primaryKey = 'id_retur_jual';

    protected $fillable = [
        'id_penjualan',
        'id_user',
        'tgl_retur',
        'total_retur',
        'keterangan',
    ];

    // ===== RELASI =====

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function detailReturPenjualans()
    {
        return $this->hasMany(DetailReturPenjualan::class, 'id_retur_jual');
    }
}
