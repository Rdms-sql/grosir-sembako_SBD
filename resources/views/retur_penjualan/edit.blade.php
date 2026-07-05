@extends('layouts.app')
@section('content')
    <div class="container">
        <form action="{{ route('retur-penjualan.update',$retur->id_retur_jual) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Penjualan</label>
            <select name="id_penjualan" class="form-control">
            @foreach($penjualan as $p)
                <option value="{{ $p->id_penjualan }}" {{ $retur->id_penjualan == $p->id_penjualan ? 'selected' : '' }}>>
                {{ $p->id_penjualan }}
                </option>
            @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Tanggal Retur</label>
            <input type="date" name="tgl_retur" class="form-control" value="{{ $retur->tgl_retur }}">
        </div>
        <div class="mb-3">
            <label>Total Retur</label>
            <input type="number" name="total_retur" class="form-control" value="{{ $retur->total_retur }}">
        </div>
        <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control">{{ $retur->keterangan }}</textarea>
        </div>
        <button class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection