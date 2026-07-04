@extends('layouts.konsumen')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">📋 Riwayat Pesanan Saya</h4>
        <a href="{{ route('katalog.index') }}" class="btn btn-primary">🛒 Pesan Lagi</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @forelse($pemesanans as $p)
    <div class="card shadow-sm mb-3 border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <div>
                <span class="fw-bold">Pesanan #{{ $p->id_pesan_konsumen }}</span>
                <span class="text-muted ms-2 small">
                    {{ \Carbon\Carbon::parse($p->tgl_pesan)->format('d/m/Y') }}
                </span>
            </div>
            @php
                $badge = match($p->status) {
                    'diproses' => 'warning',
                    'siap'     => 'info',
                    'diambil'  => 'success',
                    'batal'    => 'danger',
                    default    => 'secondary',
                };
            @endphp
            <span class="badge bg-{{ $badge }}">{{ ucfirst($p->status) }}</span>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($p->detailPesanKonsumens as $d)
                    <tr>
                        <td>{{ $d->barang->nama_barang }}</td>
                        <td>{{ $d->jumlah_pesan }} {{ $d->barang->satuan }}</td>
                        <td>Rp {{ number_format($d->barang->harga_jual, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Total</td>
                        <td class="fw-bold text-success">
                            Rp {{ number_format($p->total_pesan, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @empty
        <div class="alert alert-info">Belum ada riwayat pesanan.</div>
    @endforelse

    {{ $pemesanans->links() }}
</div>
@endsection