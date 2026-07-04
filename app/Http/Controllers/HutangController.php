<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
use App\Models\PembayaranHutang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HutangController extends Controller
{
    public function index()
    {
        $hutangs = Hutang::with('supplier')->get();
        return view('hutangs.index', compact('hutangs'));
    }

    public function show(string $id)
    {
        $hutang = Hutang::with(['supplier', 'pembayaranHutangs.user'])->findOrFail($id);
        return view('hutangs.show', compact('hutang'));
    }

    public function bayar(string $id)
    {
        $hutang = Hutang::with('supplier')->findOrFail($id);
        if ($hutang->status === 'lunas') {
            return redirect()->route('hutangs.show', $id)->with('error', 'Hutang ini sudah lunas.');
        }
        return view('hutangs.bayar', compact('hutang'));
    }

    public function simpanBayar(Request $request, string $id)
    {
        $hutang = Hutang::findOrFail($id);

        $request->validate([
            'jumlah_bayar' => [
                'required',
                'integer',
                'min:1',
                'max:' . $hutang->sisa_hutang,
            ],
            'metode_bayar' => 'required|in:tunai,transfer',
        ]);

        // Simpan pembayaran
        PembayaranHutang::create([
            'id_hutang'    => $hutang->id_hutang,
            'id_user'      => Auth::user()->id_user,
            'tgl_bayar'    => now()->toDateString(),
            'jumlah_bayar' => $request->jumlah_bayar,
            'metode_bayar' => $request->metode_bayar,
        ]);

        // Update sisa hutang
        $sisaBaru = $hutang->sisa_hutang - $request->jumlah_bayar;
        $hutang->update([
            'sisa_hutang' => $sisaBaru,
            'status'      => $sisaBaru <= 0 ? 'lunas' : 'belum_lunas',
        ]);

        return redirect()->route('hutangs.show', $id)
                         ->with('success', 'Pembayaran berhasil disimpan! Sisa hutang: Rp ' . number_format($sisaBaru, 0, ',', '.'));
    }
}