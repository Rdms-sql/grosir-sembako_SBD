<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\Hutang;
use App\Models\PemesananSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelians = Pembelian::with('supplier', 'user')->latest()->paginate(10);
        return view('pembelians.index', compact('pembelians'));
    }

    public function create()
    {
        $suppliers  = Supplier::all();
        $barangs    = Barang::all();
        $pemesanans = PemesananSupplier::with('supplier')
            ->where('status', 'diterima')
            ->get();
        return view('pembelians.create', compact('suppliers', 'barangs', 'pemesanans'));
    }

    public function store(Request $request)
    {
        $request->validate([
    'id_supplier'           => 'required|exists:suppliers,id_supplier',
    'tgl_pembelian'         => 'required|date',
    'status_bayar'          => 'required|in:cash,credit',  // ← ini di validate
    'jatuh_tempo'           => 'required_if:status_bayar,credit|nullable|date',
    'barang'                => 'required|array|min:1',
    'barang.*.id_barang'    => 'required|exists:barangs,id_barang',
    'barang.*.jumlah_beli'  => 'required|integer|min:1',
    'barang.*.harga_satuan' => 'required|integer|min:0',
]);

        DB::transaction(function () use ($request) {
            // Hitung total
            $total = 0;
            foreach ($request->barang as $item) {
                $total += $item['jumlah_beli'] * $item['harga_satuan'];
            }

            // Simpan pembelian
            $pembelian = Pembelian::create([
                'id_supplier'       => $request->id_supplier,
                'id_user'           => Auth::user()->id_user,
                'id_pesan_supplier' => $request->id_pesan_supplier ?? null,
                'tgl_pembelian'     => $request->tgl_pembelian,
                'total_beli'        => $total,
                'status_bayar'      => $request->status_bayar,
            ]);

            // Simpan detail & update stok
            foreach ($request->barang as $item) {
                DetailPembelian::create([
                    'id_pembelian' => $pembelian->id_pembelian,
                    'id_barang'    => $item['id_barang'],
                    'jumlah_beli'  => $item['jumlah_beli'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal'     => $item['jumlah_beli'] * $item['harga_satuan'],
                ]);

                // Update stok barang bertambah
                Barang::find($item['id_barang'])->increment('stok', $item['jumlah_beli']);
            }

            // Jika kredit → otomatis buat hutang
            if ($request->status_bayar === 'credit') {
                Hutang::create([
                    'id_supplier'  => $request->id_supplier,
                    'id_pembelian' => $pembelian->id_pembelian,
                    'total_hutang' => $total,
                    'sisa_hutang'  => $total,
                    'jatuh_tempo'  => $request->jatuh_tempo,
                    'status'       => 'belum_lunas',
                ]);
            }
        });

        return redirect()->route('pembelian.index')
                         ->with('success', 'Pembelian berhasil disimpan dan stok diperbarui!');
    }

    public function show(string $id)
    {
        $pembelian = Pembelian::with([
            'supplier',
            'user',
            'detailPembelians.barang',
            'hutang',
            'pemesananSupplier',
        ])->findOrFail($id);
        return view('pembelians.show', compact('pembelian'));
    }

    public function destroy(string $id)
    {
        $pembelian = Pembelian::with('detailPembelians.barang', 'hutang')->findOrFail($id);

        DB::transaction(function () use ($pembelian) {
            // Kurangi stok kembali
            foreach ($pembelian->detailPembelians as $detail) {
                $detail->barang->decrement('stok', $detail->jumlah_beli);
            }

            $pembelian->detailPembelians()->delete();

            if ($pembelian->hutang) {
                $pembelian->hutang->pembayaranHutangs()->delete();
                $pembelian->hutang->delete();
            }

            $pembelian->delete();
        });

        return redirect()->route('pembelian.index')
                         ->with('success', 'Pembelian berhasil dihapus.');
    }
}