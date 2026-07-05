@extends('layouts.app')
@section('content')
<div class="container">
    <a href="{{ route('retur-pembelian.create') }}"
        class="btn btn-primary mb-3">
        Tambah Retur
    </a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pembelian</th>
                <th>User</th>
                <th>Total</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach($retur as $r)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $r->tgl_retur }}</td>
            <td>{{ $r->pembelian->id_pembelian }}</td>
            <td>{{ $r->user->name }}</td>
            <td>Rp {{ number_format($r->total_retur,0,',','.') }}</td>
            <td>{{ $r->keterangan }}</td>
            <td>
                <a href="{{ route('retur-pembelian.edit',$r->id_retur_beli) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('retur-pembelian.destroy',$r->id_retur_beli) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Yakin?')" class="btn btn-danger btn-sm">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection