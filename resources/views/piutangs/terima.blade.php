@extends('layouts.app')
@section('title', 'Terima Pembayaran Piutang')
@section('content')

    @php
        $total = (float) $piutang->total_piutang;
        $sisa = (float) $piutang->sisa_piutang;
        $sudah = $total - $sisa;
        $persen = $total > 0 ? round(($sudah / $total) * 100) : 0;
        $persen = max(0, min(100, $persen));
    @endphp

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-cash-coin"></i> Form Penerimaan Piutang
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        Konsumen: <strong>{{ $piutang->konsumen->nama_konsumen }}</strong>
                    </p>

                    <div class="alert alert-light border mb-3">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                Total Piutang:
                                <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                            </div>
                            <div class="col-sm-4">
                                Sudah Diterima:
                                <strong class="text-success">
                                    Rp {{ number_format($sudah, 0, ',', '.') }}
                                </strong>
                            </div>
                            <div class="col-sm-4">
                                Sisa Piutang:
                                <strong class="text-danger">
                                    Rp {{ number_format($sisa, 0, ',', '.') }}
                                </strong>
                            </div>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persen }}%;"
                                aria-valuenow="{{ $persen }}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <div class="small text-muted mt-1">{{ $persen }}% terbayar</div>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('piutangs.simpan-terima', $piutang->id_piutang) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jumlah Diterima (Rp)</label>
                            <input type="number" name="jumlah_terima" class="form-control" placeholder="Masukkan jumlah..."
                                step="0.01" min="0.01" max="{{ $sisa }}" value="{{ old('jumlah_terima') }}" required>
                            <small class="text-muted">
                                Maksimal sisa piutang:
                                Rp {{ number_format($sisa, 0, ',', '.') }}
                            </small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Metode Pembayaran</label>
                            <select name="metode_bayar" class="form-select" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="tunai" @selected(old('metode_bayar') === 'tunai')>Tunai</option>
                                <option value="transfer" @selected(old('metode_bayar') === 'transfer')>Transfer</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Simpan Penerimaan
                            </button>
                            <a href="{{ route('piutangs.show', $piutang->id_piutang) }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection