@extends('layouts.app')
@section('title', 'Data Supplier')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">🚚 Data Supplier</h4>
        <a href="{{ route('supplier.create') }}" class="btn btn-primary">+ Tambah Supplier</a>
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
                        <th>Nama Supplier</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $s)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $s->nama_supplier }}</td>
                        <td>{{ $s->no_hp ?? '-' }}</td>
                        <td>{{ $s->alamat ?? '-' }}</td>
                        <td>
                            <a href="{{ route('supplier.edit', $s->id_supplier) }}"
                               class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('supplier.destroy', $s->id_supplier) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus supplier ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data supplier.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $suppliers->links() }}</div>
</div>
@endsection