<?php

namespace App\Http\Controllers;

use App\Models\ReturPembelian;
use App\Models\DetailReturPembelian;
use App\Models\Pembelian;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturPembelianController extends Controller
{
    public function index()
    {
        $returs = ReturPembelian::with('pembelian.supplier', 'user')
                    ->latest()->paginate(10);
        return view('retur-pembelians.index', compact('returs'));
    }

    public function create()
    {
        $pembelians = Pembelian::with('supplier')->latest()->get();
        return view('retur-pembelians.create', compact('pembelians'));
    }

    public function getDetailPembelian(string $id)
    {
        $pembelian = Pembelian::with('detailPembelians.barang')->findOrFail($id);
        return response()->json($pembelian->detailPembelians);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pembelian'           => 'required|exists:pembelians,id_pembelian',
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

            $retur = ReturPembelian::create([
                'id_pembelian' => $request->id_pembelian,
                'id_user'      => Auth::user()->id_user,
                'tgl_retur'    => $request->tgl_retur,
                'total_retur'  => $total,
                'keterangan'   => $request->keterangan,
            ]);

            foreach ($request->barang as $item) {
                DetailReturPembelian::create([
                    'id_retur_beli' => $retur->id_retur_beli,
                    'id_barang'     => $item['id_barang'],
                    'jumlah_retur'  => $item['jumlah_retur'],
                    'subtotal'      => $item['subtotal'],
                ]);

                // Stok berkurang karena barang dikembalikan ke supplier
                Barang::find($item['id_barang'])->decrement('stok', $item['jumlah_retur']);
            }
        });

        return redirect()->route('retur-pembelian.index')
                         ->with('success', 'Retur pembelian berhasil disimpan!');
    }

    public function show(string $id)
    {
        $retur = ReturPembelian::with([
            'pembelian.supplier',
            'user',
            'detailReturPembelians.barang',
        ])->findOrFail($id);
        return view('retur-pembelians.show', compact('retur'));
    }
}