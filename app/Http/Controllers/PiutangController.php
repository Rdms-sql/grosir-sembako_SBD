<?php

namespace App\Http\Controllers;

use App\Models\Piutang;
use App\Models\PenerimaanPiutang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PiutangController extends Controller
{
    public function index()
    {
        $piutangs = Piutang::with('konsumen')->latest()->paginate(10);
        return view('piutangs.index', compact('piutangs'));
    }

    public function show(string $id)
    {
        $piutang = Piutang::with([
            'konsumen',
            'penjualan',
            'penerimaanPiutangs.user'
        ])->findOrFail($id);
        return view('piutangs.show', compact('piutang'));
    }

    public function terima(string $id)
    {
        $piutang = Piutang::with('konsumen')->findOrFail($id);
        if ($piutang->status === 'lunas') {
            return redirect()->route('piutangs.show', $id)
                             ->with('error', 'Piutang ini sudah lunas.');
        }
        return view('piutangs.terima', compact('piutang'));
    }

    public function simpanTerima(Request $request, string $id)
    {
        $piutang = Piutang::findOrFail($id);

        $request->validate([
            'jumlah_terima' => [
                'required',
                'integer',
                'min:1',
                'max:' . $piutang->sisa_piutang,
            ],
            'metode_bayar' => 'required|in:tunai,transfer',
        ]);

        // Simpan penerimaan piutang
        PenerimaanPiutang::create([
            'id_piutang'    => $piutang->id_piutang,
            'id_user'       => Auth::user()->id_user,
            'tgl_terima'    => now()->toDateString(),
            'jumlah_terima' => $request->jumlah_terima,
            'metode_bayar'  => $request->metode_bayar,
        ]);

        // Update sisa piutang
        $sisaBaru = $piutang->sisa_piutang - $request->jumlah_terima;
        $piutang->update([
            'sisa_piutang' => $sisaBaru,
            'status'       => $sisaBaru <= 0 ? 'lunas' : 'belum_lunas',
        ]);

        return redirect()->route('piutangs.show', $id)
                         ->with('success', 'Penerimaan piutang berhasil! Sisa piutang: Rp '
                             . number_format($sisaBaru, 0, ',', '.'));
    }
}