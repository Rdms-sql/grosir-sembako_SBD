<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananSupplier extends Model
{
    protected $table = 'pemesanan_suppliers';
    protected $primaryKey = 'id_pesan_supplier';

    protected $fillable = [
        'id_supplier',
        'id_user',
        'tgl_pesan',
        'status',
        'total_pesan',
    ];

    // ===== RELASI =====

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function detailPesanSuppliers()
    {
        return $this->hasMany(DetailPesanSupplier::class, 'id_pesan_supplier');
    }

    public function pembelians()
    {
        return $this->hasMany(Pembelian::class, 'id_pesan_supplier');
    }
}
