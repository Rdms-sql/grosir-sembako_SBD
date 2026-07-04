<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id_user';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_lengkap',
        'username',
        'password',
        'role',
        'id_konsumen',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // PENTING: pakai id_user sebagai identifier
    // JANGAN override getAuthIdentifierName — biarkan default pakai primary key
    // Hanya override getAuthPassword jika perlu

    public function getAuthPassword()
    {
        return $this->password;
    }

    // ===== RELASI =====
    public function konsumen()
    {
        return $this->belongsTo(\App\Models\Konsumen::class, 'id_konsumen');
    }

    public function pembelians()
    {
        return $this->hasMany(Pembelian::class, 'id_user');
    }

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class, 'id_user');
    }

    public function pemesananSuppliers()
    {
        return $this->hasMany(PemesananSupplier::class, 'id_user');
    }

    public function pemesananKonsumens()
    {
        return $this->hasMany(PemesananKonsumen::class, 'id_user');
    }

    public function pembayaranHutangs()
    {
        return $this->hasMany(PembayaranHutang::class, 'id_user');
    }

    public function penerimaanPiutangs()
    {
        return $this->hasMany(PenerimaanPiutang::class, 'id_user');
    }

    public function returPembelians()
    {
        return $this->hasMany(ReturPembelian::class, 'id_user');
    }

    public function returPenjualans()
    {
        return $this->hasMany(ReturPenjualan::class, 'id_user');
    }
}