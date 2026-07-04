<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Konsumen extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id_konsumen';

    protected $fillable = [
        'nama_konsumen',
        'no_hp',
        'alamat',
        'limit_kredit',
    ];

    // ===== RELASI =====

    public function pemesananKonsumens()
    {
        return $this->hasMany(PemesananKonsumen::class, 'id_konsumen');
    }

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class, 'id_konsumen');
    }

    public function piutangs()
    {
        return $this->hasMany(Piutang::class, 'id_konsumen');
    }
}
