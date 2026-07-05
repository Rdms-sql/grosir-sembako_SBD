@extends('layouts.app')
@section('title', 'Detail Retur Pembelian')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-primary"><i class="bi bi-info-circle"></i> Detail Retur Pembelian</h5>
        <a href="{{ route('retur-pembelian.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr><th>Supplier</th><td>: {{ $retur->pembelian->supplier->nama_supplier }}</td></tr>
                    <tr><th>Ref. Pembelian</th><td>: <a href="{{ route('pembelian.show', $retur->id_pembelian) }}">#{{ $retur->id_pembelian }}</a></td></tr>
                    <tr><th>Tanggal Retur</th><td>: {{ \Carbon\Carbon::parse($retur->tgl_retur)->format('d M Y') }}</td></tr>
                    <tr><th>Dibuat Oleh</th><td>: {{ $retur->user->nama_lengkap }}</td></tr>
                    <tr><th>Keterangan</th><td>: {{ $retur->keterangan ?? '-' }}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <div class="alert alert-info">
                    <strong>Total Retur:</strong>
                    Rp {{ number_format($retur->total_retur, 0, ',', '.') }}
                </div>
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle"></i>
                    Stok barang sudah <strong>dikurangi kembali</strong> saat retur ini disimpan.
                </div>
            </div>
        </div>

        <h6 class="fw-bold">Detail Barang Diretur</h6>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Jumlah Retur</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($retur->detailReturPembelians as $i => $d)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $d->barang->nama_barang }}</td>
                        <td>{{ $d->barang->satuan }}</td>
                        <td>{{ $d->jumlah_retur }}</td>
                        <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Total</td>
                        <td class="fw-bold text-danger">
                            Rp {{ number_format($retur->total_retur, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection