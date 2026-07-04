@extends('layouts.app')

@section('title', 'Bayar Hutang')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-primary">
                    <i class="bi bi-wallet2"></i> Form Pembayaran Hutang
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">Supplier: <strong>{{ $hutang->supplier->nama_supplier }}</strong></p>

                {{-- Info Hutang --}}
                <div class="alert alert-light border">
                    <div class="row">
                        <div class="col-sm-4">Total Hutang: <strong>Rp {{ number_format($hutang->total_hutang, 0, ',', '.') }}</strong></div>
                        <div class="col-sm-4">Sudah Dibayar: <strong class="text-success">Rp {{ number_format($hutang->total_hutang - $hutang->sisa_hutang, 0, ',', '.') }}</strong></div>
                        <div class="col-sm-4">Sisa Hutang: <strong class="text-danger">Rp {{ number_format($hutang->sisa_hutang, 0, ',', '.') }}</strong></div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('hutangs.simpan-bayar', $hutang->id_hutang) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jumlah Bayar (Rp)</label>
                        <input type="number" name="jumlah_bayar" class="form-control" placeholder="Masukkan jumlah..." min="1" max="{{ $hutang->sisa_hutang }}" required>
                        <small class="text-muted">Maksimal sisa hutang: Rp {{ number_format($hutang->sisa_hutang, 0, ',', '.') }}</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Metode Pembayaran</label>
                        <select name="metode_bayar" class="form-select" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="tunai">Tunai</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan Pembayaran</button>
                        <a href="{{ route('hutangs.show', $hutang->id_hutang) }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection