@extends('layouts.app')
@section('title', 'Data Barang')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">📦 Data Barang</h4>
        <a href="{{ route('barang.create') }}" class="btn btn-primary">+ Tambah Barang</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nama Barang</th>
                        <th>Supplier</th>
                        <th>Satuan</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $b)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $b->nama_barang }}</td>
                        <td>{{ $b->supplier->nama_supplier }}</td>
                        <td>{{ $b->satuan }}</td>
                        <td>Rp {{ number_format($b->harga_beli, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                        <td>
                            @if($b->stok <= 5)
                                <span class="badge bg-danger">{{ $b->stok }}</span>
                            @elseif($b->stok <= 20)
                                <span class="badge bg-warning text-dark">{{ $b->stok }}</span>
                            @else
                                <span class="badge bg-success">{{ $b->stok }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('barang.edit', $b->id_barang) }}"
                               class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('barang.destroy', $b->id_barang) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus barang ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada data barang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $barangs->links() }}</div>
</div>
@endsection