<?php

namespace App\Http\Controllers;

use App\Models\ReturPenjualan;
use App\Models\DetailReturPenjualan;
use App\Models\Penjualan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturPenjualanController extends Controller
{
    public function index()
    {
        $returs = ReturPenjualan::with('penjualan.konsumen', 'user')
                    ->latest()->paginate(10);
        return view('retur-penjualans.index', compact('returs'));
    }

    public function create()
    {
        $penjualans = Penjualan::with('konsumen')->latest()->get();
        return view('retur-penjualans.create', compact('penjualans'));
    }

    public function getDetailPenjualan(string $id)
    {
        $penjualan = Penjualan::with('detailPenjualans.barang')->findOrFail($id);
        return response()->json($penjualan->detailPenjualans);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_penjualan'           => 'required|exists:penjualans,id_penjualan',
            'tgl_retur'              => 'required|date',
            'keterangan'             => 'nullable|string',
            'barang'                 => 'required|array|min:1',
            'barang.*.id_barang'     => 'required|exists:barangs,id_barang',
            'barang.*.jumlah_retur'  => 'required|integer|min:1',
            'barang.*.subtotal'      => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $total = 0;
            foreach ($request->barang as $item) {
                $total += $item['subtotal'];
            }

            $retur = ReturPenjualan::create([
                'id_penjualan' => $request->id_penjualan,
                'id_user'      => Auth::user()->id_user,
                'tgl_retur'    => $request->tgl_retur,
                'total_retur'  => $total,
                'keterangan'   => $request->keterangan,
            ]);

            foreach ($request->barang as $item) {
                DetailReturPenjualan::create([
                    'id_retur_jual' => $retur->id_retur_jual,
                    'id_barang'     => $item['id_barang'],
                    'jumlah_retur'  => $item['jumlah_retur'],
                    'subtotal'      => $item['subtotal'],
                ]);

                // Stok bertambah kembali karena barang dikembalikan
                Barang::find($item['id_barang'])->increment('stok', $item['jumlah_retur']);
            }
        });

        return redirect()->route('retur-penjualan.index')
                         ->with('success', 'Retur penjualan berhasil disimpan!');
    }

    public function show(string $id)
    {
        $retur = ReturPenjualan::with([
            'penjualan.konsumen',
            'user',
            'detailReturPenjualans.barang',
        ])->findOrFail($id);
        return view('retur-penjualans.show', compact('retur'));
    }
}