<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\PemesananKonsumen;
use App\Models\DetailPesanKonsumen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KatalogController extends Controller
{
    // Halaman katalog barang untuk konsumen
    public function index()
    {
        $barangs = Barang::where('stok', '>', 0)->get();
        return view('katalog.index', compact('barangs'));
    }

    // Riwayat pesanan konsumen yang login
    public function riwayat()
    {
        $pemesanans = PemesananKonsumen::with('detailPesanKonsumens.barang')
            ->where('id_konsumen', Auth::user()->id_konsumen)
            ->latest()
            ->paginate(10);
        return view('katalog.riwayat', compact('pemesanans'));
    }

    // Simpan pesanan dari konsumen
    public function pesan(Request $request)
    {
        $request->validate([
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
                'id_konsumen' => Auth::user()->id_konsumen,
                'id_user'     => Auth::user()->id_user,
                'tgl_pesan'   => now()->toDateString(),
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

        return redirect()->route('katalog.riwayat')
                         ->with('success', 'Pesanan berhasil dibuat! Kami akan segera memproses pesanan Anda.');
    }
}