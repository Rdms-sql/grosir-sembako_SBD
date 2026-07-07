<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *@property string|null $sisa_piutang
 */

class Piutang extends Model
{
    protected $primaryKey = 'id_piutang';

    protected $fillable = [
        'id_konsumen',
        'id_penjualan',
        'total_piutang',
        'sisa_piutang',
        'jatuh_tempo',
        'status',
    ];

    protected $casts = [
        'total_piutang' => 'decimal:2',
        'sisa_piutang' => 'decimal:2',
        'jatuh_tempo' => 'date',
    ];

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'id_konsumen', 'id_konsumen');
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan', 'id_penjualan');
    }

    public function penerimaanPiutangs()
    {
        return $this->hasMany(PenerimaanPiutang::class, 'id_piutang', 'id_piutang');
    }

    /**
     * Hitung ulang sisa_piutang berdasarkan total_piutang dikurangi
     * seluruh penerimaan yang tercatat, lalu simpan.
     *
     * Dipanggil setiap kali ada perubahan pada PenerimaanPiutang terkait.
     */
    public function updateSisaPiutang(): void
    {
        $totalDiterima = $this->penerimaanPiutangs()->sum('jumlah_terima');

        $sisa = $this->total_piutang - $totalDiterima;

        // Guard sederhana: sisa tidak boleh negatif akibat human error input lebih besar dari piutang.
        $this->sisa_piutang = max($sisa, 0);

        // Opsional tapi masuk akal: auto-update status kalau lunas.
        $this->status = $this->sisa_piutang <= 0 ? 'lunas' : 'belum_lunas';

        $this->save(); // saveQuietly agar tidak memicu event 'updating'/'updated' berulang

    }
}
