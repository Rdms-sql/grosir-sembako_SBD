@extends('layouts.app')
@section('content')
<div class="container">
    <form action="{{ route('retur-pembelian.update',$retur->id_retur_beli) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Pembelian</label>
            <select name="id_pembelian" class="form-control">
            @foreach($pembelian as $p)
                <option value="{{ $p->id_pembelian }}" {{ $retur->id_pembelian==$p->id_pembelian ? 'selected' : '' }}>
                {{ $p->id_pembelian }}
                </option>
            @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tgl_retur" class="form-control" value="{{ $retur->tgl_retur }}">
        </div>
        <div class="mb-3">
            <label>Total</label>
            <input type="number" name="total_retur" class="form-control" value="{{ $retur->total_retur }}">
        </div>
        <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control" value="{{ $retur->keterangan }}"></textarea>
        </div>
        <button class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection