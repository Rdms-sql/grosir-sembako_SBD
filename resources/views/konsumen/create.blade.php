@extends('layouts.app')
@section('title', 'Tambah Konsumen')
@section('content')
<div class="container-fluid">
    <h4 class="fw-bold mb-3">➕ Tambah Konsumen</h4>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('konsumen.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Konsumen <span class="text-danger">*</span></label>
                        <input type="text" name="nama_konsumen" class="form-control"
                               value="{{ old('nama_konsumen') }}" placeholder="Nama lengkap" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">No. HP</label>
                        <input type="text" name="no_hp" class="form-control"
                               value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3"
                                  placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Limit Kredit <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="limit_kredit" class="form-control"
                                   value="{{ old('limit_kredit', 0) }}" min="0" required>
                        </div>
                        <small class="text-muted">Isi 0 jika tidak ada limit kredit</small>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">💾 Simpan</button>
                    <a href="{{ route('konsumen.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection