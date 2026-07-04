@extends('layouts.app')

@section('title', 'Detail Pemesanan Supplier')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">🔍 Detail Pemesanan Supplier</h4>
        <a href="{{ route('pemesanan-supplier.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Informasi Pemesanan</div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr><th>Supplier</th><td>{{ $pemesanan->supplier->nama_supplier }}</td></tr>
                        <tr><th>Tanggal Pesan</th><td>{{ \Carbon\Carbon::parse($pemesanan->tgl_pesan)->format('d/m/Y') }}</td></tr>
                        <tr><th>Dibuat Oleh</th><td>{{ $pemesanan->user->nama_lengkap }}</td></tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @php
                                    $badge = match($pemesanan->status) {
                                        'diajukan' => 'warning',
                                        'diterima' => 'success',
                                        'batal'    => 'danger',
                                        default    => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }} fs-6">{{ ucfirst($pemesanan->status) }}</span>
                            </td>
                        </tr>
                        <tr><th>Total</th><td class="fw-bold text-success fs-5">Rp {{ number_format($pemesanan->total_pesan, 0, ',', '.') }}</td></tr>
                    </table>
                </div>
            </div>

            {{-- Update Status --}}
            <div class="card shadow-sm mt-3">
                <div class="card-header fw-bold">Update Status</div>
                <div class="card-body">
                    <form action="{{ route('pemesanan-supplier.updateStatus', $pemesanan->id_pesan_supplier) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="d-flex gap-2">
                            <select name="status" class="form-select">
                                <option value="diajukan" {{ $pemesanan->status === 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                <option value="diterima" {{ $pemesanan->status === 'diterima' ? 'selected' : '' }}>Diterima</option>
                                <option value="batal"    {{ $pemesanan->status === 'batal'    ? 'selected' : '' }}>Batal</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Detail Barang</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Harga Beli</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pemesanan->detailPesanSuppliers as $i => $d)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $d->barang->nama_barang }}</td>
                                <td>{{ $d->barang->satuan }}</td>
                                <td>Rp {{ number_format($d->barang->harga_beli, 0, ',', '.') }}</td>
                                <td>{{ $d->jumlah_pesan }}</td>
                                <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Total</td>
                                <td class="fw-bold text-success">Rp {{ number_format($pemesanan->total_pesan, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection