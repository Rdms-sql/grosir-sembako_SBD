<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('supplier')->latest()->paginate(10);
        return view('barang.index', compact('barangs'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('barang.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_supplier'  => 'required|exists:suppliers,id_supplier',
            'nama_barang'  => 'required|string|max:100|unique:barangs,nama_barang',
            'harga_beli'   => 'required|numeric|min:0',
            'harga_jual'   => 'required|numeric|min:0',
            'satuan'       => 'required|string|max:20',
            'stok'         => 'required|integer|min:0',
        ]);

        Barang::create($request->only(
            'id_supplier',
            'nama_barang',
            'harga_beli',
            'harga_jual',
            'satuan',
            'stok'
        ));

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $barang    = Barang::findOrFail($id);
        $suppliers = Supplier::all();
        return view('barang.edit', compact('barang', 'suppliers'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'id_supplier'  => 'required|exists:suppliers,id_supplier',
            'nama_barang'  => [
                'required',
                'string',
                'max:100',
                Rule::unique('barangs', 'nama_barang')->ignore($id, 'id_barang'),
            ],
            'harga_beli'   => 'required|numeric|min:0',
            'harga_jual'   => 'required|numeric|min:0',
            'satuan'       => 'required|string|max:20',
            'stok'         => 'required|integer|min:0',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->only(
            'id_supplier',
            'nama_barang',
            'harga_beli',
            'harga_jual',
            'satuan',
            'stok'
        ));

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $barang = Barang::findOrFail($id);

        $masihDipakai = $barang->detailPenjualans()->exists()
            || $barang->detailPembelians()->exists()
            || $barang->detailPesanSuppliers()->exists()
            || $barang->detailPesanKonsumens()->exists();

        if ($masihDipakai) {
            return redirect()->route('barang.index')
                ->with('error', 'Barang tidak bisa dihapus karena masih memiliki riwayat transaksi terkait.');
        }

        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}
