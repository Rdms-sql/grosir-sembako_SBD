@extends('layouts.app')
@section('content')
<div class="container">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form action="{{ route('retur-pembelian.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Pembelian</label>
            <select name="id_pembelian" class="form-control">
            @foreach($pembelian as $p)
                <option value="{{ $p->id_pembelian }}">
                {{ $p->id_pembelian }}
                </option>
            @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tgl_retur" class="form-control">
        </div>
        <div class="mb-3">
            <label>Total</label>
            <input type="number" name="total_retur" class="form-control">
        </div>
        <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection