<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id_supplier';

    protected $fillable = [
        'nama_supplier',
        'no_hp',
        'alamat',
    ];

    // ===== RELASI =====

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'id_supplier', 'id_supplier');
    }

    public function pemesananSuppliers()
    {
        return $this->hasMany(PemesananSupplier::class, 'id_supplier', 'id_supplier');
    }

    public function pembelians()
    {
        return $this->hasMany(Pembelian::class, 'id_supplier', 'id_supplier');
    }

    public function hutangs()
    {
        return $this->hasMany(Hutang::class, 'id_supplier', 'id_supplier');
    }
}
