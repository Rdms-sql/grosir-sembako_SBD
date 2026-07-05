@extends('layouts.app')
@section('title', 'Daftar Piutang')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-primary">
            <i class="bi bi-arrow-up-circle"></i> Daftar Piutang Konsumen
        </h5>
        <span class="text-muted small">Kelola penerimaan piutang dari konsumen</span>
    </div>
    <div class="card-body p-0">
        @if(session('success'))
            <div class="alert alert-success m-3">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Konsumen</th>
                        <th>Total Piutang</th>
                        <th>Sisa Piutang</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($piutangs as $index => $p)
                    <tr>
                        <td class="ps-4">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold">{{ $p->konsumen->nama_konsumen }}</div>
                            <small class="text-muted">ID Penjualan: {{ $p->id_penjualan }}</small>
                        </td>
                        <td>Rp {{ number_format($p->total_piutang, 0, ',', '.') }}</td>
                        <td class="text-danger fw-bold">
                            Rp {{ number_format($p->sisa_piutang, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="badge {{ \Carbon\Carbon::parse($p->jatuh_tempo)->isPast() && $p->status !== 'lunas' ? 'bg-danger' : 'bg-warning text-dark' }}">
                                {{ \Carbon\Carbon::parse($p->jatuh_tempo)->format('d M Y') }}
                            </span>
                        </td>
                        <td>
                            @if($p->status === 'lunas')
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Lunas
                                </span>
                            @else
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-clock"></i> Belum Lunas
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('piutangs.show', $p->id_piutang) }}"
                               class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i> Belum ada data piutang.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $piutangs->links() }}</div>
@endsection