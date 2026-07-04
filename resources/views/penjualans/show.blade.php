@extends('layouts.app')
@section('title', 'Detail Penjualan')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-primary"><i class="bi bi-info-circle"></i> Detail Penjualan</h5>
        <a href="{{ route('penjualan.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr><th>Konsumen</th><td>: {{ $penjualan->konsumen->nama_konsumen }}</td></tr>
                    <tr><th>Tanggal</th><td>: {{ \Carbon\Carbon::parse($penjualan->tgl_penjualan)->format('d M Y') }}</td></tr>
                    <tr><th>Dibuat Oleh</th><td>: {{ $penjualan->user->nama_lengkap }}</td></tr>
                    @if($penjualan->pemesananKonsumen)
                    <tr>
                        <th>Ref. Pemesanan</th>
                        <td>: <a href="{{ route('pemesanan-konsumen.show', $penjualan->id_pesan_konsumen) }}">#{{ $penjualan->id_pesan_konsumen }}</a></td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th>Total Penjualan</th>
                        <td>: <strong class="text-success">Rp {{ number_format($penjualan->total_jual, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <th>Jenis Pembayaran</th>
                        <td>:
                            @if($penjualan->status_bayar === 'cash')
                                <span class="badge bg-success">Cash</span>
                            @else
                                <span class="badge bg-warning text-dark">Credit</span>
                            @endif
                        </td>
                    </tr>
                </table>

                @if($penjualan->piutang)
                <div class="alert alert-warning py-2 mt-2">
                    <small><strong>Piutang:</strong>
                    Sisa Rp {{ number_format($penjualan->piutang->sisa_piutang, 0, ',', '.') }}
                    — Status: {{ $penjualan->piutang->status }}
                    — <a href="{{ route('piutangs.show', $penjualan->piutang->id_piutang) }}">Lihat Detail Piutang</a>
                    </small>
                </div>
                @endif
            </div>
        </div>

        <h6 class="fw-bold">Detail Barang</h6>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Harga Jual</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penjualan->detailPenjualans as $i => $d)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $d->barang->nama_barang }}</td>
                        <td>{{ $d->barang->satuan }}</td>
                        <td>Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                        <td>{{ $d->jumlah_jual }}</td>
                        <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="5" class="text-end fw-bold">Total</td>
                        <td class="fw-bold text-success">
                            Rp {{ number_format($penjualan->total_jual, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection