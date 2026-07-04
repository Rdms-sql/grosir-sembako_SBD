<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hutang extends Model
{
    protected $table = 'hutangs';
    protected $primaryKey = 'id_hutang';
    public $incrementing = true; // Pastikan ini sesuai dengan tabel Anda
    
    protected $fillable = [
        'id_pembelian', 
        'id_supplier', 
        'total_hutang', 
        'sisa_hutang', 
        'jatuh_tempo', 
        'status'
    ];

    // ===== RELASI =====

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian', 'id_pembelian');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id_supplier');
    }

    public function pembayaranHutangs()
    {
        return $this->hasMany(PembayaranHutang::class, 'id_hutang', 'id_hutang');
    }

    // ===== ATTRIBUTES =====

    public function getTotalDibayarAttribute()
    {
        return $this->pembayaranHutangs()->sum('jumlah_bayar');
    }
}