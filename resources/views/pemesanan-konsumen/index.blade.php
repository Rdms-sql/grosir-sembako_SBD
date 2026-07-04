@extends('layouts.app')

@section('title', 'Pemesanan Konsumen')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">📋 Pemesanan Konsumen</h4>
        <a href="{{ route('pemesanan-konsumen.create') }}" class="btn btn-primary">
            + Buat Pemesanan
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Konsumen</th>
                        <th>Dibuat Oleh</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pemesanans as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tgl_pesan)->format('d/m/Y') }}</td>
                        <td>{{ $p->konsumen->nama_konsumen }}</td>
                        <td>{{ $p->user->nama_lengkap }}</td>
                        <td>Rp {{ number_format($p->total_pesan, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $badge = match($p->status) {
                                    'diproses' => 'warning',
                                    'siap'     => 'info',
                                    'diambil'  => 'success',
                                    'batal'    => 'danger',
                                    default    => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-{{ $badge }}">{{ ucfirst($p->status) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('pemesanan-konsumen.show', $p->id_pesan_konsumen) }}"
                               class="btn btn-sm btn-info text-white">Detail</a>
                            @if($p->status === 'diproses')
                                <a href="{{ route('pemesanan-konsumen.edit', $p->id_pesan_konsumen) }}"
                                   class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('pemesanan-konsumen.destroy', $p->id_pesan_konsumen) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin hapus pemesanan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada pemesanan konsumen.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $pemesanans->links() }}</div>
</div>
@endsection