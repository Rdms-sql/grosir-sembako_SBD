@extends('layouts.app')

@section('title', 'Detail Pemesanan Konsumen')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">🔍 Detail Pemesanan Konsumen</h4>
        <a href="{{ route('pemesanan-konsumen.index') }}" class="btn btn-secondary">← Kembali</a>
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

    <div class="row g-3">
        {{-- Kolom Kiri: Info + Update Status --}}
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Informasi Pemesanan</div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="130">Konsumen</th>
                            <td>{{ $pemesanan->konsumen->nama_konsumen }}</td>
                        </tr>
                        <tr>
                            <th>No. HP</th>
                            <td>{{ $pemesanan->konsumen->no_hp ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pesan</th>
                            <td>{{ \Carbon\Carbon::parse($pemesanan->tgl_pesan)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat Oleh</th>
                            <td>{{ $pemesanan->user->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @php
                                    $badge = match($pemesanan->status) {
                                        'diproses' => 'warning',
                                        'siap'     => 'info',
                                        'diambil'  => 'success',
                                        'batal'    => 'danger',
                                        default    => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }} fs-6">
                                    {{ ucfirst($pemesanan->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td class="fw-bold text-success fs-5">
                                Rp {{ number_format($pemesanan->total_pesan, 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Update Status --}}
            <div class="card shadow-sm mt-3">
                <div class="card-header fw-bold">Update Status</div>
                <div class="card-body">
                    <form action="{{ route('pemesanan-konsumen.updateStatus', $pemesanan->id_pesan_konsumen) }}"
                          method="POST">
                        @csrf @method('PATCH')
                        <div class="d-flex gap-2">
                            <select name="status" class="form-select">
                                <option value="diproses"
                                    {{ $pemesanan->status === 'diproses' ? 'selected' : '' }}>
                                    Diproses
                                </option>
                                <option value="siap"
                                    {{ $pemesanan->status === 'siap' ? 'selected' : '' }}>
                                    Siap
                                </option>
                                <option value="diambil"
                                    {{ $pemesanan->status === 'diambil' ? 'selected' : '' }}>
                                    Diambil
                                </option>
                                <option value="batal"
                                    {{ $pemesanan->status === 'batal' ? 'selected' : '' }}>
                                    Batal
                                </option>
                            </select>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Tabel Detail Barang --}}
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
                                <th>Harga Jual</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pemesanan->detailPesanKonsumens as $i => $d)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $d->barang->nama_barang }}</td>
                                <td>{{ $d->barang->satuan }}</td>
                                <td>Rp {{ number_format($d->barang->harga_jual, 0, ',', '.') }}</td>
                                <td>{{ $d->jumlah_pesan }}</td>
                                <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Total</td>
                                <td class="fw-bold text-success">
                                    Rp {{ number_format($pemesanan->total_pesan, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection