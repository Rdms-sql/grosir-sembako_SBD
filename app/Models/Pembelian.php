<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $primaryKey = 'id_pembelian';

    protected $fillable = [
        'id_supplier',
        'id_user',
        'id_pesan_supplier',
        'tgl_pembelian',
        'total_beli',
        'status_bayar',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id_supplier');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function pemesananSupplier()
    {
        return $this->belongsTo(PemesananSupplier::class, 'id_pesan_supplier', 'id_pesan_supplier');
    }

    public function detailPembelians()
    {
        return $this->hasMany(DetailPembelian::class, 'id_pembelian');
    }

    public function hutang()
    {
        return $this->hasOne(Hutang::class, 'id_pembelian');
    }
}