<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('supplier.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:100|unique:suppliers,nama_supplier',
            'no_hp'         => 'nullable|string|max:15',
            'alamat'        => 'nullable|string',
        ]);

        Supplier::create($request->only('nama_supplier', 'no_hp', 'alamat'));

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'nama_supplier' => [
                'required',
                'string',
                'max:100',
                Rule::unique('suppliers', 'nama_supplier')->ignore($id, 'id_supplier'),
            ],
            'no_hp'  => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->only('nama_supplier', 'no_hp', 'alamat'));

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $supplier = Supplier::findOrFail($id);

        if ($supplier->barangs()->exists()) {
            return redirect()->route('supplier.index')
                ->with('error', 'Supplier tidak bisa dihapus karena masih memiliki data barang terkait.');
        }

        $supplier->delete();

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }
}
