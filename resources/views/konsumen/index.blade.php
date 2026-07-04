@extends('layouts.app')
@section('title', 'Data Konsumen')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">👥 Data Konsumen</h4>
        <a href="{{ route('konsumen.create') }}" class="btn btn-primary">+ Tambah Konsumen</a>
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
                        <th>Nama Konsumen</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                        <th>Limit Kredit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($konsumens as $k)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $k->nama_konsumen }}</td>
                        <td>{{ $k->no_hp ?? '-' }}</td>
                        <td>{{ $k->alamat ?? '-' }}</td>
                        <td>Rp {{ number_format($k->limit_kredit, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('konsumen.edit', $k->id_konsumen) }}"
                               class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('konsumen.destroy', $k->id_konsumen) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus konsumen ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data konsumen.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $konsumens->links() }}</div>
</div>
@endsection