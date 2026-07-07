@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-receipt-cutoff"></i> Data Piutang</h4>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Stat cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Piutang Aktif</div>
                        <div class="fs-4 fw-semibold">{{ $totalAktif }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Lunas</div>
                        <div class="fs-4 fw-semibold text-success">{{ $totalLunas }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Total Piutang</div>
                        <div class="fs-4 fw-semibold">{{ $totalAktif + $totalLunas }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Nominal Outstanding</div>
                        <div class="fs-5 fw-semibold text-danger">
                            Rp {{ number_format((float) $totalOutstanding, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel piutang --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Konsumen</th>
                                <th>Total Piutang</th>
                                <th>Sisa Piutang</th>
                                <th style="width: 180px;">Progress</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($piutangs as $piutang)
                                @php
                                    $total = (float) $piutang->total_piutang;
                                    $sisa = (float) $piutang->sisa_piutang;
                                    $persen = $total > 0
                                        ? round((($total - $sisa) / $total) * 100)
                                        : 100;
                                    $persen = max(0, min(100, $persen));
                                @endphp
                                <tr>
                                    <td>{{ $piutangs->firstItem() + $loop->index }}</td>
                                    <td>{{ $piutang->konsumen->nama_konsumen ?? '-' }}</td>
                                    <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($sisa, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {{ $persen }}%;" aria-valuenow="{{ $persen }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                        <div class="small text-muted mt-1">{{ $persen }}% terbayar</div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($piutang->jatuh_tempo)->format('d M Y') }}</td>
                                    <td>
                                        @if ($piutang->status === 'lunas')
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Belum Lunas</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('piutangs.show', $piutang->id_piutang) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Belum ada data piutang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $piutangs->links() }}
            </div>
        </div>

    </div>
@endsection