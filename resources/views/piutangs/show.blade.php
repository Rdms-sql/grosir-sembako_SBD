@extends('layouts.app')
@section('title', 'Detail Piutang')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-primary">
            <i class="bi bi-info-circle"></i> Detail Piutang Konsumen
        </h5>
        <a href="{{ route('piutangs.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr><th>Konsumen</th><td>: {{ $piutang->konsumen->nama_konsumen }}</td></tr>
                    <tr><th>No. HP</th><td>: {{ $piutang->konsumen->no_hp ?? '-' }}</td></tr>
                    <tr>
                        <th>Ref. Penjualan</th>
                        <td>: <a href="{{ route('penjualan.show', $piutang->id_penjualan) }}">#{{ $piutang->id_penjualan }}</a></td>
                    </tr>
                    <tr><th>Jatuh Tempo</th><td>: {{ \Carbon\Carbon::parse($piutang->jatuh_tempo)->format('d M Y') }}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th>Total Piutang</th>
                        <td>: Rp {{ number_format($piutang->total_piutang, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Sudah Diterima</th>
                        <td class="text-success">
                            : Rp {{ number_format($piutang->total_piutang - $piutang->sisa_piutang, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <th>Sisa Piutang</th>
                        <td class="text-danger fw-bold">
                            : Rp {{ number_format($piutang->sisa_piutang, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>:
                            @if($piutang->status === 'lunas')
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-danger">Belum Lunas</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        @if($piutang->status !== 'lunas')
            <a href="{{ route('piutangs.terima', $piutang->id_piutang) }}"
               class="btn btn-primary mb-4">
                <i class="bi bi-cash-coin"></i> Terima Pembayaran
            </a>
        @endif

        <h6 class="fw-bold">Riwayat Penerimaan</h6>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jumlah Diterima</th>
                        <th>Metode</th>
                        <th>Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($piutang->penerimaanPiutangs as $index => $t)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($t->tgl_terima)->format('d M Y') }}</td>
                        <td class="text-success fw-bold">
                            Rp {{ number_format($t->jumlah_terima, 0, ',', '.') }}
                        </td>
                        <td>{{ ucfirst($t->metode_bayar) }}</td>
                        <td>{{ $t->user->nama_lengkap ?? 'Admin' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Belum ada riwayat penerimaan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection