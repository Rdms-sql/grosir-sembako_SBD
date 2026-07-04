@extends('layouts.app')

@section('title', 'Data Penjualan')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-cash-stack text-success"></i> Data Penjualan</h4>
        <a href="{{ route('penjualan.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Penjualan
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#1e293b; color:#fff;">
                    <tr>
                        <th>#</th>
                        <th>No. Transaksi</th>
                        <th>Tanggal</th>
                        <th>Konsumen</th>
                        <th>Kasir</th>
                        <th>Status Bayar</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualans as $index => $penjualan)
                        <tr>
                            <td>{{ $penjualans->firstItem() + $index }}</td>
                            <td>PJ-{{ str_pad($penjualan->id_penjualan, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ \Carbon\Carbon::parse($penjualan->tgl_penjualan)->format('d-m-Y') }}</td>
                            <td>{{ $penjualan->konsumen->nama_konsumen ?? '-' }}</td>
                            <td>{{ $penjualan->user->nama_lengkap ?? '-' }}</td>
                            <td>
                                @if($penjualan->status_bayar === 'cash')
                                    <span class="badge bg-success">Cash</span>
                                @else
                                    <span class="badge bg-warning text-dark">Credit</span>
                                @endif
                            </td>
                            <td class="text-end">Rp {{ number_format($penjualan->total_jual, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ route('penjualan.show', $penjualan->id_penjualan) }}"
                                   class="btn btn-sm btn-outline-secondary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('penjualan.edit', $penjualan->id_penjualan) }}"
                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('penjualan.destroy', $penjualan->id_penjualan) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Stok barang akan dikembalikan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Belum ada data penjualan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $penjualans->links() }}
    </div>

@endsection 