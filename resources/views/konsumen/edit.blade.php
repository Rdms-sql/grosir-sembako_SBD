@extends('layouts.app')
@section('title', 'Edit Konsumen')
@section('content')
<div class="container-fluid">
    <h4 class="fw-bold mb-3">✏️ Edit Konsumen</h4>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('konsumen.update', $konsumen->id_konsumen) }}" method="POST">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Konsumen <span class="text-danger">*</span></label>
                        <input type="text" name="nama_konsumen" class="form-control"
                               value="{{ old('nama_konsumen', $konsumen->nama_konsumen) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">No. HP</label>
                        <input type="text" name="no_hp" class="form-control"
                               value="{{ old('no_hp', $konsumen->no_hp) }}">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $konsumen->alamat) }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Limit Kredit <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="limit_kredit" class="form-control"
                                   value="{{ old('limit_kredit', $konsumen->limit_kredit) }}" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">💾 Update</button>
                    <a href="{{ route('konsumen.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection