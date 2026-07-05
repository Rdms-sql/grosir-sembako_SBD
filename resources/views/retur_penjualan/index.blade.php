@extends('layouts.app')
@section('content')
<div class="container">
    <a href="{{ route('retur-penjualan.create') }}" class="btn btn-primary mb-3">Tambah Retur Penjualan</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Penjualan</th>
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
                <td>{{ $r->penjualan->id_penjualan }}</td>
                <td>{{ $r->user->name }}</td>
                <td>Rp {{ number_format($r->total_retur,0,',','.') }}</td>
                <td>{{ $r->keterangan }}</td>
                <td>
                    <a href="{{ route('retur-penjualan.edit',$r->id_retur_jual) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('retur-penjualan.destroy',$r->id_retur_jual) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection