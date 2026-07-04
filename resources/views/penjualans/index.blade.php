@extends('layouts.app')
@section('title', 'Daftar Penjualan')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-primary"><i class="bi bi-cash-stack"></i> Daftar Penjualan</h5>
        <a href="{{ route('penjualan.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Tambah Penjualan
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
                        <th>Konsumen</th>
                        <th>Total</th>
                        <th>Jenis</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualans as $index => $p)
                    <tr>
                        <td class="ps-4">{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tgl_penjualan)->format('d M Y') }}</td>
                        <td><div class="fw-bold">{{ $p->konsumen->nama_konsumen }}</div></td>
                        <td>Rp {{ number_format($p->total_jual, 0, ',', '.') }}</td>
                        <td>
                            @if($p->status_bayar === 'cash')
                                <span class="badge bg-success">Cash</span>
                            @else
                                <span class="badge bg-warning text-dark">Credit</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('penjualan.show', $p->id_penjualan) }}"
                               class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            <form action="{{ route('penjualan.destroy', $p->id_penjualan) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus? Stok akan dikembalikan!')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i> Belum ada data penjualan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $penjualans->links() }}</div>
@endsection