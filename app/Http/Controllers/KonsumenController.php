<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Konsumen;
use Illuminate\Validation\Rule;

class KonsumenController extends Controller
{
    public function index()
    {
        $konsumens = Konsumen::latest()->paginate(10);
        return view('konsumen.index', compact('konsumens'));
    }

    public function create()
    {
        return view('konsumen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_konsumen' => 'required|string|max:100|unique:konsumens,nama_konsumen',
            'no_hp'         => 'nullable|string|max:15',
            'alamat'        => 'nullable|string',
            'limit_kredit'  => 'required|numeric|min:0',
        ]);

        Konsumen::create($request->only('nama_konsumen', 'no_hp', 'alamat', 'limit_kredit'));

        return redirect()->route('konsumen.index')
            ->with('success', 'Konsumen berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $konsumen = Konsumen::findOrFail($id);
        return view('konsumen.edit', compact('konsumen'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'nama_konsumen' => [
                'required',
                'string',
                'max:100',
                Rule::unique('konsumens', 'nama_konsumen')->ignore($id, 'id_konsumen'),
            ],
            'no_hp'        => 'nullable|string|max:15',
            'alamat'       => 'nullable|string',
            'limit_kredit' => 'required|numeric|min:0',
        ]);

        $konsumen = Konsumen::findOrFail($id);
        $konsumen->update($request->only('nama_konsumen', 'no_hp', 'alamat', 'limit_kredit'));

        return redirect()->route('konsumen.index')
            ->with('success', 'Konsumen berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $konsumen = Konsumen::findOrFail($id);

        if ($konsumen->piutangs()->exists() || $konsumen->penjualans()->exists()) {
            return redirect()->route('konsumen.index')
                ->with('error', 'Konsumen tidak bisa dihapus karena masih memiliki data piutang atau penjualan terkait.');
        }

        $konsumen->delete();

        return redirect()->route('konsumen.index')
            ->with('success', 'Konsumen berhasil dihapus.');
    }
}
