<?php

namespace App\Http\Controllers;

use App\Models\ReturPembelian;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturPembelianController extends Controller
{
    public function index()
    {
        $retur = ReturPembelian::with('pembelian','user')->latest()->get();
        return view('retur_pembelian.index', compact('retur'));
    }

    public function create()
    {
        $pembelian = Pembelian::all();
        return view('retur_pembelian.create', compact('pembelian'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pembelian'=>'required',
            'tgl_retur'=>'required|date',
            'total_retur'=>'required|numeric',
            'keterangan'=>'nullable'
        ]);

        ReturPembelian::create([
            'id_pembelian'=>$request->id_pembelian,
            'id_user'=>Auth::id(),
            'tgl_retur'=>$request->tgl_retur,
            'total_retur'=>$request->total_retur,
            'keterangan'=>$request->keterangan,
        ]);

        return redirect()->route('retur-pembelian.index')->with('success','Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $retur = ReturPembelian::findOrFail($id);
        $pembelian = Pembelian::all();
        return view('retur_pembelian.edit', compact('retur','pembelian'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pembelian'=>'required',
            'tgl_retur'=>'required|date',
            'total_retur'=>'required|numeric',
            'keterangan'=>'nullable'
        ]);

        $retur = ReturPembelian::findOrFail($id);

        $retur->update([
            'id_pembelian'=>$request->id_pembelian,
            'tgl_retur'=>$request->tgl_retur,
            'total_retur'=>$request->total_retur,
            'keterangan'=>$request->keterangan,
        ]);

        return redirect()
                ->route('retur-pembelian.index')
                ->with('success','Data berhasil diubah');
    }

    public function destroy($id)
    {
        ReturPembelian::findOrFail($id)->delete();

        return redirect()->route('retur-pembelian.index')->with('success','Data berhasil dihapus');
    }
}