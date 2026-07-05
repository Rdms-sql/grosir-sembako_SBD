@extends('layouts.app')
@section('title', 'Terima Pembayaran Piutang')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-primary">
                    <i class="bi bi-cash-coin"></i> Form Penerimaan Piutang
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    Konsumen: <strong>{{ $piutang->konsumen->nama_konsumen }}</strong>
                </p>

                <div class="alert alert-light border mb-4">
                    <div class="row">
                        <div class="col-sm-4">
                            Total Piutang:
                            <strong>Rp {{ number_format($piutang->total_piutang, 0, ',', '.') }}</strong>
                        </div>
                        <div class="col-sm-4">
                            Sudah Diterima:
                            <strong class="text-success">
                                Rp {{ number_format($piutang->total_piutang - $piutang->sisa_piutang, 0, ',', '.') }}
                            </strong>
                        </div>
                        <div class="col-sm-4">
                            Sisa Piutang:
                            <strong class="text-danger">
                                Rp {{ number_format($piutang->sisa_piutang, 0, ',', '.') }}
                            </strong>
                        </div>
                    </div>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('piutangs.simpan-terima', $piutang->id_piutang) }}"
                      method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jumlah Diterima (Rp)</label>
                        <input type="number" name="jumlah_terima" class="form-control"
                               placeholder="Masukkan jumlah..."
                               min="1" max="{{ $piutang->sisa_piutang }}" required>
                        <small class="text-muted">
                            Maksimal sisa piutang:
                            Rp {{ number_format($piutang->sisa_piutang, 0, ',', '.') }}
                        </small>
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
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Simpan Penerimaan
                        </button>
                        <a href="{{ route('piutangs.show', $piutang->id_piutang) }}"
                           class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection