<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id_barang';

    protected $fillable = [
        'id_supplier',
        'nama_barang',
        'harga_beli',
        'harga_jual',
        'satuan',
        'stok',
    ];

    // ===== RELASI =====

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function detailPesanSuppliers()
    {
        return $this->hasMany(DetailPesanSupplier::class, 'id_barang');
    }

    public function detailPesanKonsumens()
    {
        return $this->hasMany(DetailPesanKonsumen::class, 'id_barang');
    }

    public function detailPembelians()
    {
        return $this->hasMany(DetailPembelian::class, 'id_barang');
    }

    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_barang');
    }

    public function detailReturPembelians()
    {
        return $this->hasMany(DetailReturPembelian::class, 'id_barang');
    }

    public function detailReturPenjualans()
    {
        return $this->hasMany(DetailReturPenjualan::class, 'id_barang');
    }
}
