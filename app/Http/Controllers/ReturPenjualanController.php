<?php

namespace App\Http\Controllers;

use App\Models\ReturPenjualan;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturPenjualanController extends Controller
{
    public function index()
    {
        $retur = ReturPenjualan::with('penjualan', 'user')
                    ->latest()
                    ->get();

        return view('retur_penjualan.index', compact('retur'));
    }

    public function create()
    {
        $penjualan = Penjualan::all();

        return view('retur_penjualan.create', compact('penjualan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_penjualan' => 'required',
            'tgl_retur' => 'required|date',
            'total_retur' => 'required|numeric',
            'keterangan' => 'nullable',
        ]);

        ReturPenjualan::create([
            'id_penjualan' => $request->id_penjualan,
            'id_user' => Auth::id(),
            'tgl_retur' => $request->tgl_retur,
            'total_retur' => $request->total_retur,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('retur-penjualan.index')->with('success', 'Data retur penjualan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $retur = ReturPenjualan::findOrFail($id);
        $penjualan = Penjualan::all();

        return view('retur_penjualan.edit', compact('retur', 'penjualan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_penjualan' => 'required',
            'tgl_retur' => 'required|date',
            'total_retur' => 'required|numeric',
            'keterangan' => 'nullable',
        ]);

        $retur = ReturPenjualan::findOrFail($id);

        $retur->update([
            'id_penjualan' => $request->id_penjualan,
            'tgl_retur' => $request->tgl_retur,
            'total_retur' => $request->total_retur,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()
            ->route('retur-penjualan.index')
            ->with('success', 'Data retur penjualan berhasil diubah.');
    }

    public function destroy($id)
    {
        ReturPenjualan::findOrFail($id)->delete();

        return redirect()
            ->route('retur-penjualan.index')
            ->with('success', 'Data retur penjualan berhasil dihapus.');
    }
}