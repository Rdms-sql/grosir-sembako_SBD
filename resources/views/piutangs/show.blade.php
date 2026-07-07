@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-receipt-cutoff"></i> Detail Piutang</h4>
            <a href="{{ route('piutangs.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @php
            $total = (float) $piutang->total_piutang;
            $sisa = (float) $piutang->sisa_piutang;
            $persen = $total > 0 ? round((($total - $sisa) / $total) * 100) : 100;
            $persen = max(0, min(100, $persen));
        @endphp

        <div class="row g-3">
            {{-- Kolom kiri: info piutang --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="text-muted small">Konsumen</div>
                                <div class="fs-5 fw-semibold">{{ $piutang->konsumen->nama_konsumen ?? '-' }}</div>
                            </div>
                            @if ($piutang->status === 'lunas')
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-warning text-dark">Belum Lunas</span>
                            @endif
                        </div>

                        <table class="table table-sm mb-3">
                            <tr>
                                <td class="text-muted">Total Piutang</td>
                                <td class="text-end fw-semibold">Rp {{ number_format($total, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Sisa Piutang</td>
                                <td class="text-end fw-semibold text-danger">Rp {{ number_format($sisa, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Jatuh Tempo</td>
                                <td class="text-end">{{ \Carbon\Carbon::parse($piutang->jatuh_tempo)->format('d M Y') }}
                                </td>
                            </tr>
                            @if ($piutang->penjualan)
                                <tr>
                                    <td class="text-muted">No. Penjualan</td>
                                    <td class="text-end">#{{ $piutang->penjualan->id_penjualan }}</td>
                                </tr>
                            @endif
                        </table>

                        <div>
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>Progress pembayaran</span>
                                <span>{{ $persen }}%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persen }}%;"
                                    aria-valuenow="{{ $persen }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($piutang->status !== 'lunas')
                    <a href="{{ route('piutangs.terima', $piutang->id_piutang) }}" class="btn btn-primary w-100">
                        <i class="bi bi-cash-coin"></i> Terima Pembayaran
                    </a>
                @endif
            </div>

            {{-- Kolom kanan: timeline riwayat --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title mb-3"><i class="bi bi-clock-history"></i> Riwayat Pembayaran</h6>

                        @php
                            $riwayat = $piutang->penerimaanPiutangs->sortByDesc('tgl_terima');
                        @endphp

                        @forelse ($riwayat as $penerimaan)
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-circle bg-success d-flex align-items-center justify-content-center"
                                        style="width: 36px; height: 36px;">
                                        <i class="bi bi-check-lg text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 border-bottom pb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-semibold">
                                            Rp {{ number_format((float) $penerimaan->jumlah_terima, 0, ',', '.') }}
                                        </span>
                                        <span class="text-muted small">
                                            {{ \Carbon\Carbon::parse($penerimaan->tgl_terima)->format('d M Y') }}
                                        </span>
                                    </div>
                                    <div class="small text-muted">
                                        {{ ucfirst($penerimaan->metode_bayar) }}
                                        @if ($penerimaan->user)
                                            &middot; diterima oleh {{ $penerimaan->user->nama_lengkap }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-4 mb-0">Belum ada riwayat pembayaran.</p>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection