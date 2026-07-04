@extends('layouts.app')

@section('title', 'Detail Hutang')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-primary"><i class="bi bi-info-circle"></i> Detail Hutang Supplier</h5>
        <a href="{{ route('hutangs.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        
        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr><th>Nama Supplier</th><td>: {{ $hutang->supplier->nama_supplier }}</td></tr>
                    <tr><th>No. HP</th><td>: {{ $hutang->supplier->no_hp ?? '-' }}</td></tr>
                    <tr><th>Jatuh Tempo</th><td>: {{ \Carbon\Carbon::parse($hutang->jatuh_tempo)->format('d M Y') }}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr><th>Total Hutang</th><td>: Rp {{ number_format($hutang->total_hutang, 0, ',', '.') }}</td></tr>
                    <tr><th>Sudah Dibayar</th><td class="text-success">: Rp {{ number_format($hutang->total_hutang - $hutang->sisa_hutang, 0, ',', '.') }}</td></tr>
                    <tr><th>Sisa Hutang</th><td class="text-danger fw-bold">: Rp {{ number_format($hutang->sisa_hutang, 0, ',', '.') }}</td></tr>
                    <tr><th>Status</th><td>: @if($hutang->status == 'lunas') <span class="badge bg-success">Lunas</span> @else <span class="badge bg-danger">Belum Lunas</span> @endif</td></tr>
                </table>
            </div>
        </div>

        @if($hutang->status !== 'lunas')
            <a href="{{ route('hutangs.bayar', $hutang->id_hutang) }}" class="btn btn-primary mb-4"><i class="bi bi-credit-card"></i> Bayar Hutang</a>
        @endif

        <h6>Riwayat Pembayaran</h6>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                        <th>Metode</th>
                        <th>Dicatat oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hutang->pembayaranHutangs as $index => $bayar)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($bayar->tgl_bayar)->format('d M Y') }}</td>
                        <td class="text-success fw-bold">Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($bayar->metode_bayar) }}</td>
                        <td>{{ $bayar->user->name ?? 'Admin' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted">Belum ada riwayat pembayaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection