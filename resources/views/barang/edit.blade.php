@extends('layouts.app')
@section('title', 'Edit Barang')
@section('content')
<div class="container-fluid">
    <h4 class="fw-bold mb-3">✏️ Edit Barang</h4>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('barang.update', $barang->id_barang) }}" method="POST">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                        <select name="id_supplier" class="form-select" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id_supplier }}"
                                    {{ $barang->id_supplier == $s->id_supplier ? 'selected' : '' }}>
                                    {{ $s->nama_supplier }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang" class="form-control"
                               value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Harga Beli <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_beli" class="form-control"
                                   value="{{ old('harga_beli', $barang->harga_beli) }}" min="0" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Harga Jual <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_jual" class="form-control"
                                   value="{{ old('harga_jual', $barang->harga_jual) }}" min="0" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="satuan" class="form-control"
                               value="{{ old('satuan', $barang->satuan) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                        <input type="number" name="stok" class="form-control"
                               value="{{ old('stok', $barang->stok) }}" min="0" required>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">💾 Update</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection