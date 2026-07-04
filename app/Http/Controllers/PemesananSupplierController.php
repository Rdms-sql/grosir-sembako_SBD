<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PemesananSupplier;
use App\Models\DetailPesanSupplier;
use App\Models\Supplier;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PemesananSupplierController extends Controller
{
    public function index()
    {
        $pemesanans = PemesananSupplier::with('supplier', 'user')
            ->latest()
            ->paginate(10);
        return view('pemesanan-supplier.index', compact('pemesanans'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $barangs   = Barang::all();
        return view('pemesanan-supplier.create', compact('suppliers', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_supplier'       => 'required|exists:suppliers,id_supplier',
            'tgl_pesan'         => 'required|date',
            'barang'            => 'required|array|min:1',
            'barang.*.id_barang'    => 'required|exists:barangs,id_barang',
            'barang.*.jumlah_pesan' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            // Hitung total
            $total = 0;
            foreach ($request->barang as $item) {
                $barang = Barang::find($item['id_barang']);
                $total += $barang->harga_beli * $item['jumlah_pesan'];
            }

            // Simpan header
            $pesan = PemesananSupplier::create([
                'id_supplier' => $request->id_supplier,
                'id_user'     => Auth::id(),
                'tgl_pesan'   => $request->tgl_pesan,
                'status'      => 'diajukan',
                'total_pesan' => $total,
            ]);

            // Simpan detail
            foreach ($request->barang as $item) {
                $barang = Barang::find($item['id_barang']);
                DetailPesanSupplier::create([
                    'id_pesan_supplier' => $pesan->id_pesan_supplier,
                    'id_barang'         => $item['id_barang'],
                    'jumlah_pesan'      => $item['jumlah_pesan'],
                    'subtotal'          => $barang->harga_beli * $item['jumlah_pesan'],
                ]);
            }
        });

        return redirect()->route('pemesanan-supplier.index')
                         ->with('success', 'Pemesanan supplier berhasil disimpan.');
    }

    public function show(int $id)
    {
        $pemesanan = PemesananSupplier::with('supplier', 'user', 'detailPesanSuppliers.barang')
            ->findOrFail($id);
        return view('pemesanan-supplier.show', compact('pemesanan'));
    }

    public function edit(int $id)
    {
        $pemesanan = PemesananSupplier::with('detailPesanSuppliers.barang')->findOrFail($id);

        // Hanya boleh edit jika masih "diajukan"
        if ($pemesanan->status !== 'diajukan') {
            return redirect()->route('pemesanan-supplier.index')
                             ->with('error', 'Pemesanan tidak bisa diedit karena sudah '.$pemesanan->status.'.');
        }

        $suppliers = Supplier::all();
        $barangs   = Barang::all();
        return view('pemesanan-supplier.edit', compact('pemesanan', 'suppliers', 'barangs'));
    }

    public function update(Request $request,int $id)
    {
        $pemesanan = PemesananSupplier::findOrFail($id);

        if ($pemesanan->status !== 'diajukan') {
            return redirect()->route('pemesanan-supplier.index')
                             ->with('error', 'Pemesanan tidak bisa diubah.');
        }

        $request->validate([
            'id_supplier'           => 'required|exists:suppliers,id_supplier',
            'tgl_pesan'             => 'required|date',
            'barang'                => 'required|array|min:1',
            'barang.*.id_barang'    => 'required|exists:barangs,id_barang',
            'barang.*.jumlah_pesan' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $pemesanan) {
            $total = 0;
            foreach ($request->barang as $item) {
                $barang = Barang::find($item['id_barang']);
                $total += $barang->harga_beli * $item['jumlah_pesan'];
            }

            $pemesanan->update([
                'id_supplier' => $request->id_supplier,
                'tgl_pesan'   => $request->tgl_pesan,
                'total_pesan' => $total,
            ]);

            // Hapus detail lama, isi ulang
            $pemesanan->detailPesanSuppliers()->delete();

            foreach ($request->barang as $item) {
                $barang = Barang::find($item['id_barang']);
                DetailPesanSupplier::create([
                    'id_pesan_supplier' => $pemesanan->id_pesan_supplier,
                    'id_barang'         => $item['id_barang'],
                    'jumlah_pesan'      => $item['jumlah_pesan'],
                    'subtotal'          => $barang->harga_beli * $item['jumlah_pesan'],
                ]);
            }
        });

        return redirect()->route('pemesanan-supplier.index')
                         ->with('success', 'Pemesanan berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $pemesanan = PemesananSupplier::findOrFail($id);

        if ($pemesanan->status !== 'diajukan') {
            return redirect()->route('pemesanan-supplier.index')
                             ->with('error', 'Pemesanan yang sudah diproses tidak bisa dihapus.');
        }

        $pemesanan->detailPesanSuppliers()->delete();
        $pemesanan->delete();

        return redirect()->route('pemesanan-supplier.index')
                         ->with('success', 'Pemesanan berhasil dihapus.');
    }

    public function updateStatus(Request $request,int $id)
    {
        $request->validate([
            'status' => 'required|in:diajukan,diterima,batal',
        ]);

        $pemesanan = PemesananSupplier::findOrFail($id);
        $pemesanan->update(['status' => $request->status]);

        return redirect()->route('pemesanan-supplier.show', $id)
                         ->with('success', 'Status berhasil diperbarui.');
    }
}