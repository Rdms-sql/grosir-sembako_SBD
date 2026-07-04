@extends('layouts.app')
@section('title', 'Detail Pembelian')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-primary"><i class="bi bi-info-circle"></i> Detail Pembelian</h5>
        <a href="{{ route('pembelian.index') }}" class="btn btn-sm btn-outline-secondary">
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
                    <tr><th>Supplier</th><td>: {{ $pembelian->supplier->nama_supplier }}</td></tr>
                    <tr><th>Tanggal</th><td>: {{ \Carbon\Carbon::parse($pembelian->tgl_pembelian)->format('d M Y') }}</td></tr>
                    <tr><th>Dibuat Oleh</th><td>: {{ $pembelian->user->nama_lengkap }}</td></tr>
                    @if($pembelian->pemesananSupplier)
                    <tr>
                        <th>Ref. Pemesanan</th>
                        <td>: <a href="{{ route('pemesanan-supplier.show', $pembelian->id_pesan_supplier) }}">#{{ $pembelian->id_pesan_supplier }}</a></td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th>Total Pembelian</th>
                        <td>: <strong class="text-success">Rp {{ number_format($pembelian->total_beli, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <th>Jenis Pembayaran</th>
                        <td>:
                            @if($pembelian->status_bayar === 'cash')
                                <span class="badge bg-success">Cash</span>
                            @else
                                <span class="badge bg-warning text-dark">Credit</span>
                            @endif
                        </td>
                    </tr>
                    <tr><th>Status</th><td>: <span class="badge bg-info">{{ ucfirst($pembelian->status) }}</span></td></tr>
                </table>

                @if($pembelian->hutang)
                <div class="alert alert-warning py-2 mt-2">
                    <small><strong>Hutang:</strong>
                    Sisa Rp {{ number_format($pembelian->hutang->sisa_hutang, 0, ',', '.') }}
                    — Status: {{ $pembelian->hutang->status }}
                    — <a href="{{ route('hutangs.show', $pembelian->hutang->id_hutang) }}">Lihat Detail Hutang</a>
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
                        <th>Harga Satuan</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pembelian->detailPembelians as $i => $d)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $d->barang->nama_barang }}</td>
                        <td>{{ $d->barang->satuan }}</td>
                        <td>Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                        <td>{{ $d->jumlah_beli }}</td>
                        <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="5" class="text-end fw-bold">Total</td>
                        <td class="fw-bold text-success">
                            Rp {{ number_format($pembelian->total_beli, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection