@extends('layouts.app')

@section('title', 'Daftar Hutang')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-primary">
            <i class="bi bi-arrow-down-circle"></i> Daftar Hutang ke Supplier
        </h5>
        <span class="text-muted small">Kelola pembayaran hutang secara berkala</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Supplier</th>
                        <th>Total Hutang</th>
                        <th>Sisa Hutang</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hutangs as $index => $hutang)
                    <tr>
                        <td class="ps-4">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold">{{ $hutang->supplier->nama_supplier ?? 'N/A' }}</div>
                            <small class="text-muted">ID Transaksi: {{ $hutang->id_pembelian ?? 'N/A' }}</small>
                        </td>
                        <td>Rp {{ number_format($hutang->total_hutang, 0, ',', '.') }}</td>
                        <td class="text-danger fw-bold">Rp {{ number_format($hutang->sisa_hutang, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ \Carbon\Carbon::parse($hutang->jatuh_tempo)->isPast() ? 'bg-danger' : 'bg-warning text-dark' }}">
                                {{ \Carbon\Carbon::parse($hutang->jatuh_tempo)->format('d M Y') }}
                            </span>
                        </td>
                        <td>
                            @if($hutang->status == 'lunas')
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Lunas</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> Belum Lunas</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('hutangs.show', $hutang->id_hutang) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i> Belum ada data hutang.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection