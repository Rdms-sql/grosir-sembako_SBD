@extends('layouts.app')
@section('title', 'Tambah Retur Pembelian')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-primary"><i class="bi bi-plus-circle"></i> Tambah Retur Pembelian</h5>
        <a href="{{ route('retur-pembelian.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('retur-pembelian.store') }}" method="POST">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-md-5">
                    <label class="form-label fw-bold">Pembelian <span class="text-danger">*</span></label>
                    <select name="id_pembelian" class="form-select" id="selectPembelian" required>
                        <option value="">-- Pilih Pembelian --</option>
                        @foreach($pembelians as $p)
                            <option value="{{ $p->id_pembelian }}">
                                #{{ $p->id_pembelian }} — {{ $p->supplier->nama_supplier }}
                                ({{ \Carbon\Carbon::parse($p->tgl_pembelian)->format('d M Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tanggal Retur <span class="text-danger">*</span></label>
                    <input type="date" name="tgl_retur" class="form-control"
                           value="{{ old('tgl_retur', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control"
                           placeholder="Alasan retur..." value="{{ old('keterangan') }}">
                </div>
            </div>

            <div class="card border mb-4">
                <div class="card-header fw-bold">
                    Detail Barang yang Diretur
                    <small class="text-muted fw-normal">— Pilih pembelian dulu</small>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Barang</th>
                                <th width="130">Harga Satuan</th>
                                <th width="130">Jml Dibeli</th>
                                <th width="130">Jml Retur</th>
                                <th width="160">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="bodyBarang">
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    Pilih pembelian untuk memuat detail barang
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="4" class="text-end fw-bold">Total Retur</td>
                                <td class="fw-bold text-danger" id="totalLabel">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Retur
                </button>
                <a href="{{ route('retur-pembelian.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('selectPembelian').addEventListener('change', function() {
    var id = this.value;
    if (!id) {
        document.getElementById('bodyBarang').innerHTML =
            '<tr><td colspan="5" class="text-center text-muted py-3">Pilih pembelian untuk memuat detail barang</td></tr>';
        document.getElementById('totalLabel').textContent = 'Rp 0';
        return;
    }

    fetch('/retur-pembelian/detail/' + id)
        .then(function(res) { return res.json(); })
        .then(function(details) {
            var tbody = document.getElementById('bodyBarang');
            tbody.innerHTML = '';

            details.forEach(function(d, i) {
                var tr = document.createElement('tr');
                tr.innerHTML =
                    '<td>' + d.barang.nama_barang + ' (' + d.barang.satuan + ')'
                    + '<input type="hidden" name="barang[' + i + '][id_barang]" value="' + d.id_barang + '">'
                    + '</td>'
                    + '<td>Rp ' + parseInt(d.harga_satuan).toLocaleString("id-ID") + '</td>'
                    + '<td>' + d.jumlah_beli + '</td>'
                    + '<td><input type="number" name="barang[' + i + '][jumlah_retur]" class="form-control form-control-sm jumlah-retur" min="1" max="' + d.jumlah_beli + '" value="1" data-harga="' + d.harga_satuan + '" required></td>'
                    + '<td><input type="hidden" name="barang[' + i + '][subtotal]" class="subtotal-hidden" value="' + d.harga_satuan + '">'
                    + '<span class="subtotal-text">Rp ' + parseInt(d.harga_satuan).toLocaleString("id-ID") + '</span></td>';
                tbody.appendChild(tr);

                tr.querySelector('.jumlah-retur').addEventListener('input', function() {
                    var harga  = parseInt(this.dataset.harga) || 0;
                    var jumlah = parseInt(this.value) || 0;
                    var sub    = harga * jumlah;
                    tr.querySelector('.subtotal-hidden').value = sub;
                    tr.querySelector('.subtotal-text').textContent = 'Rp ' + sub.toLocaleString('id-ID');
                    hitungTotal();
                });
            });

            hitungTotal();
        });
});

function hitungTotal() {
    var total = 0;
    document.querySelectorAll('.subtotal-hidden').forEach(function(el) {
        total += parseInt(el.value) || 0;
    });
    document.getElementById('totalLabel').textContent = 'Rp ' + total.toLocaleString('id-ID');
}
</script>
@endsection