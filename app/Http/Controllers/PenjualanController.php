<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Barang;
use App\Models\Konsumen;
use App\Models\Piutang;
use App\Models\PemesananKonsumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::with('konsumen', 'user')->latest()->paginate(10);
        return view('penjualans.index', compact('penjualans'));
    }

    public function create()
    {
        $konsumens  = Konsumen::all();
        $barangs    = Barang::where('stok', '>', 0)->get();
        $pemesanans = PemesananKonsumen::with('konsumen')
            ->where('status', 'siap')
            ->get();
        return view('penjualans.create', compact('konsumens', 'barangs', 'pemesanans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_konsumen'           => 'required|exists:konsumens,id_konsumen',
            'tgl_penjualan'         => 'required|date',
            'status_bayar'          => 'required|in:cash,credit',
            'jatuh_tempo'           => 'required_if:status_bayar,credit|nullable|date',
            'barang'                => 'required|array|min:1',
            'barang.*.id_barang'    => 'required|exists:barangs,id_barang',
            'barang.*.jumlah_jual'  => 'required|integer|min:1',
            'barang.*.harga_satuan' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            // Hitung total
            $total = 0;
            foreach ($request->barang as $item) {
                $total += $item['jumlah_jual'] * $item['harga_satuan'];
            }

            // Simpan penjualan
            $penjualan = Penjualan::create([
                'id_konsumen'       => $request->id_konsumen,
                'id_user'           => Auth::user()->id_user,
                'id_pesan_konsumen' => $request->id_pesan_konsumen ?? null,
                'tgl_penjualan'     => $request->tgl_penjualan,
                'total_jual'        => $total,
                'status_bayar'      => $request->status_bayar,
            ]);

            // Simpan detail & kurangi stok
            foreach ($request->barang as $item) {
                DetailPenjualan::create([
                    'id_penjualan' => $penjualan->id_penjualan,
                    'id_barang'    => $item['id_barang'],
                    'jumlah_jual'  => $item['jumlah_jual'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal'     => $item['jumlah_jual'] * $item['harga_satuan'],
                ]);

                // Stok barang berkurang
                Barang::find($item['id_barang'])->decrement('stok', $item['jumlah_jual']);
            }

            // Jika credit → otomatis buat piutang
            if ($request->status_bayar === 'credit') {
                Piutang::create([
                    'id_konsumen'   => $request->id_konsumen,
                    'id_penjualan'  => $penjualan->id_penjualan,
                    'total_piutang' => $total,
                    'sisa_piutang'  => $total,
                    'jatuh_tempo'   => $request->jatuh_tempo,
                    'status'        => 'belum_lunas',
                ]);
            }

            // Update status pemesanan jadi diambil
            if ($request->id_pesan_konsumen) {
                PemesananKonsumen::find($request->id_pesan_konsumen)
                    ->update(['status' => 'diambil']);
            }
        });

        return redirect()->route('penjualan.index')
                         ->with('success', 'Penjualan berhasil disimpan!');
    }

    public function show(string $id)
    {
        $penjualan = Penjualan::with([
            'konsumen',
            'user',
            'detailPenjualans.barang',
            'piutang',
            'pemesananKonsumen',
        ])->findOrFail($id);
        return view('penjualans.show', compact('penjualan'));
    }

    public function destroy(string $id)
    {
        $penjualan = Penjualan::with('detailPenjualans.barang', 'piutang')->findOrFail($id);

        DB::transaction(function () use ($penjualan) {
            // Kembalikan stok
            foreach ($penjualan->detailPenjualans as $detail) {
                $detail->barang->increment('stok', $detail->jumlah_jual);
            }

            $penjualan->detailPenjualans()->delete();

            if ($penjualan->piutang) {
                $penjualan->piutang->penerimaanPiutangs()->delete();
                $penjualan->piutang->delete();
            }

            $penjualan->delete();
        });

        return redirect()->route('penjualan.index')
                         ->with('success', 'Penjualan berhasil dihapus.');
    }
}