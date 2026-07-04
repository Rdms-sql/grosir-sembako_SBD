<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PemesananKonsumen;
use App\Models\DetailPesanKonsumen;
use App\Models\Konsumen;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PemesananKonsumenController extends Controller
{
    public function index()
    {
        $pemesanans = PemesananKonsumen::with('konsumen', 'user')
            ->latest()
            ->paginate(10);
        return view('pemesanan-konsumen.index', compact('pemesanans'));
    }

    public function create()
    {
        $konsumens = Konsumen::all();
        $barangs   = Barang::all();
        return view('pemesanan-konsumen.create', compact('konsumens', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_konsumen'           => 'required|exists:konsumens,id_konsumen',
            'tgl_pesan'             => 'required|date',
            'barang'                => 'required|array|min:1',
            'barang.*.id_barang'    => 'required|exists:barangs,id_barang',
            'barang.*.jumlah_pesan' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $total = 0;
            foreach ($request->barang as $item) {
                $barang = Barang::find($item['id_barang']);
                $total += $barang->harga_jual * $item['jumlah_pesan'];
            }

            $pesan = PemesananKonsumen::create([
                'id_konsumen' => $request->id_konsumen,
                'id_user'     => Auth::id(),
                'tgl_pesan'   => $request->tgl_pesan,
                'status'      => 'diproses',
                'total_pesan' => $total,
            ]);

            foreach ($request->barang as $item) {
                $barang = Barang::find($item['id_barang']);
                DetailPesanKonsumen::create([
                    'id_pesan_konsumen' => $pesan->id_pesan_konsumen,
                    'id_barang'         => $item['id_barang'],
                    'jumlah_pesan'      => $item['jumlah_pesan'],
                    'subtotal'          => $barang->harga_jual * $item['jumlah_pesan'],
                ]);
            }
        });

        return redirect()->route('pemesanan-konsumen.index')
                         ->with('success', 'Pemesanan konsumen berhasil disimpan.');
    }

    public function show(int $id)
    {
        $pemesanan = PemesananKonsumen::with('konsumen', 'user', 'detailPesanKonsumens.barang')
            ->findOrFail($id);
        return view('pemesanan-konsumen.show', compact('pemesanan'));
    }

    public function edit(int $id)
    {
        $pemesanan = PemesananKonsumen::with('detailPesanKonsumens.barang')->findOrFail($id);

        if ($pemesanan->status !== 'diproses') {
            return redirect()->route('pemesanan-konsumen.index')
                             ->with('error', 'Pemesanan tidak bisa diedit, status: '.$pemesanan->status.'.');
        }

        $konsumens = Konsumen::all();
        $barangs   = Barang::all();
        return view('pemesanan-konsumen.edit', compact('pemesanan', 'konsumens', 'barangs'));
    }

    public function update(Request $request,int $id)
    {
        $pemesanan = PemesananKonsumen::findOrFail($id);

        if ($pemesanan->status !== 'diproses') {
            return redirect()->route('pemesanan-konsumen.index')
                             ->with('error', 'Pemesanan tidak bisa diubah.');
        }

        $request->validate([
            'id_konsumen'           => 'required|exists:konsumens,id_konsumen',
            'tgl_pesan'             => 'required|date',
            'barang'                => 'required|array|min:1',
            'barang.*.id_barang'    => 'required|exists:barangs,id_barang',
            'barang.*.jumlah_pesan' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $pemesanan) {
            $total = 0;
            foreach ($request->barang as $item) {
                $barang = Barang::find($item['id_barang']);
                $total += $barang->harga_jual * $item['jumlah_pesan'];
            }

            $pemesanan->update([
                'id_konsumen' => $request->id_konsumen,
                'tgl_pesan'   => $request->tgl_pesan,
                'total_pesan' => $total,
            ]);

            $pemesanan->detailPesanKonsumens()->delete();

            foreach ($request->barang as $item) {
                $barang = Barang::find($item['id_barang']);
                DetailPesanKonsumen::create([
                    'id_pesan_konsumen' => $pemesanan->id_pesan_konsumen,
                    'id_barang'         => $item['id_barang'],
                    'jumlah_pesan'      => $item['jumlah_pesan'],
                    'subtotal'          => $barang->harga_jual * $item['jumlah_pesan'],
                ]);
            }
        });

        return redirect()->route('pemesanan-konsumen.index')
                         ->with('success', 'Pemesanan berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $pemesanan = PemesananKonsumen::findOrFail($id);

        if ($pemesanan->status !== 'diproses') {
            return redirect()->route('pemesanan-konsumen.index')
                             ->with('error', 'Pemesanan yang sudah diproses tidak bisa dihapus.');
        }

        $pemesanan->detailPesanKonsumens()->delete();
        $pemesanan->delete();

        return redirect()->route('pemesanan-konsumen.index')
                         ->with('success', 'Pemesanan berhasil dihapus.');
    }

    public function updateStatus(Request $request,int $id)
    {
        $request->validate([
            'status' => 'required|in:diproses,siap,diambil,batal',
        ]);

        $pemesanan = PemesananKonsumen::findOrFail($id);
        $pemesanan->update(['status' => $request->status]);

        return redirect()->route('pemesanan-konsumen.show', $id)
                         ->with('success', 'Status berhasil diperbarui.');
    }
}