@extends('layouts.app')
@section('title', 'Retur Pembelian')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-primary">
            <i class="bi bi-arrow-return-left"></i> Retur Pembelian
        </h5>
        <a href="{{ route('retur-pembelian.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Tambah Retur
        </a>
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
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Total Retur</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returs as $i => $r)
                    <tr>
                        <td class="ps-4">{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($r->tgl_retur)->format('d M Y') }}</td>
                        <td><div class="fw-bold">{{ $r->pembelian->supplier->nama_supplier }}</div></td>
                        <td>Rp {{ number_format($r->total_retur, 0, ',', '.') }}</td>
                        <td>{{ $r->keterangan ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('retur-pembelian.show', $r->id_retur_beli) }}"
                               class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i> Belum ada data retur pembelian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $returs->links() }}</div>
@endsection