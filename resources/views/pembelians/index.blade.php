@extends('layouts.app')
@section('title', 'Daftar Pembelian')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-primary"><i class="bi bi-receipt"></i> Daftar Pembelian</h5>
        <a href="{{ route('pembelian.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Tambah Pembelian
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
                        <th>Total</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembelians as $index => $p)
                    <tr>
                        <td class="ps-4">{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tgl_pembelian)->format('d M Y') }}</td>
                        <td><div class="fw-bold">{{ $p->supplier->nama_supplier }}</div></td>
                        <td>Rp {{ number_format($p->total_pembelian, 0, ',', '.') }}</td>
                        <td>
                            @if($p->jenis_pembayaran === 'cash')
                                <span class="badge bg-success">Cash</span>
                            @else
                                <span class="badge bg-warning text-dark">Credit</span>
                            @endif
                        </td>
                        <td><span class="badge bg-info">{{ ucfirst($p->status) }}</span></td>
                        <td class="text-center">
                            <a href="{{ route('pembelian.show', $p->id_pembelian) }}"
                               class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            <form action="{{ route('pembelian.destroy', $p->id_pembelian) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus pembelian ini? Stok akan dikurangi kembali!')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i> Belum ada data pembelian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $pembelians->links() }}</div>
@endsection