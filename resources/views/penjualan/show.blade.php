@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-receipt"></i>
            Detail Transaksi PJ-{{ str_pad($penjualan->id_penjualan, 5, '0', STR_PAD_LEFT) }}
        </h4>
        <div>
            <a href="{{ route('penjualan.edit', $penjualan->id_penjualan) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p class="mb-1 text-muted small">Konsumen</p>
                    <p class="fw-semibold">{{ $penjualan->konsumen->nama_konsumen ?? '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="mb-1 text-muted small">Tanggal Penjualan</p>
                    <p class="fw-semibold">{{ \Carbon\Carbon::parse($penjualan->tgl_penjualan)->format('d-m-Y') }}</p>
                </div>
                <div class="col-md-3">
                    <p class="mb-1 text-muted small">Status Bayar</p>
                    <p>
                        @if($penjualan->status_bayar === 'cash')
                            <span class="badge bg-success">Cash</span>
                        @else
                            <span class="badge bg-warning text-dark">Credit</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-2">
                    <p class="mb-1 text-muted small">Kasir</p>
                    <p class="fw-semibold">{{ $penjualan->user->nama_lengkap ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-semibold">Item Barang</div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead style="background:#1e293b; color:#fff;">
                    <tr>
                        <th>#</th>
                        <th>Nama Barang</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualan->detailPenjualans as $i => $detail)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                            <td class="text-end">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $detail->jumlah_jual }}</td>
                            <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Tidak ada item.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-semibold">Total</td>
                        <td class="text-end fw-semibold">Rp {{ number_format($penjualan->total_jual, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection