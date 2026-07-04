@extends('layouts.app')

@section('title', 'Edit Pemesanan Supplier')

@section('content')
<div class="container-fluid">
    <h4 class="fw-bold mb-3">✏️ Edit Pemesanan Supplier</h4>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pemesanan-supplier.update', $pemesanan->id_pesan_supplier) }}" method="POST">
        @csrf @method('PUT')

        <div class="card shadow-sm mb-3">
            <div class="card-header fw-bold">Informasi Pemesanan</div>
            <div class="card-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Supplier</label>
                    <select name="id_supplier" class="form-select" required>
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id_supplier }}"
                                {{ $pemesanan->id_supplier == $s->id_supplier ? 'selected' : '' }}>
                                {{ $s->nama_supplier }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Pesan</label>
                    <input type="date" name="tgl_pesan" class="form-control"
                           value="{{ $pemesanan->tgl_pesan }}" required>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                Detail Barang
                <button type="button" class="btn btn-sm btn-success" onclick="tambahBaris()">
                    + Tambah Barang
                </button>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead class="table-secondary">
                        <tr>
                            <th>Barang</th>
                            <th width="160">Harga Beli</th>
                            <th width="150">Jumlah</th>
                            <th width="170">Subtotal</th>
                            <th width="60"></th>
                        </tr>
                    </thead>
                    <tbody id="bodyBarang"></tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="3" class="text-end fw-bold">Total</td>
                            <td class="fw-bold text-success" id="totalLabel">Rp 0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">💾 Update</button>
            <a href="{{ route('pemesanan-supplier.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
    var daftarBarang = [];
    @foreach($barangs as $b)
    daftarBarang.push({
        id:         {{ $b->id_barang }},
        nama:       "{{ addslashes($b->nama_barang) }}",
        harga_beli: {{ $b->harga_beli }},
        satuan:     "{{ addslashes($b->satuan) }}"
    });
    @endforeach

    var existingDetails = [];
    @foreach($pemesanan->detailPesanSuppliers as $d)
    existingDetails.push({
        id_barang:    {{ $d->id_barang }},
        jumlah_pesan: {{ $d->jumlah_pesan }}
    });
    @endforeach

    var index = 0;

    function tambahBaris(idBarang, jumlah) {
        idBarang = idBarang || '';
        jumlah   = jumlah   || 1;

        var tbody = document.getElementById('bodyBarang');
        var tr    = document.createElement('tr');

        var optionsHtml = '<option value="">-- Pilih --</option>';
        daftarBarang.forEach(function(b) {
            var selected = (b.id == idBarang) ? 'selected' : '';
            optionsHtml += '<option value="' + b.id + '" data-harga="' + b.harga_beli + '" '
                         + selected + '>' + b.nama + ' (' + b.satuan + ')</option>';
        });

        var harga    = 0;
        var found    = daftarBarang.find(function(b) { return b.id == idBarang; });
        if (found) harga = found.harga_beli;
        var subtotal = harga * jumlah;

        tr.innerHTML =
            '<td><select name="barang[' + index + '][id_barang]" '
            + 'class="form-select form-select-sm select-barang" required>'
            + optionsHtml + '</select></td>'
            + '<td><input type="text" class="form-control form-control-sm harga-beli" '
            + 'readonly value="' + formatRupiah(harga) + '"></td>'
            + '<td><input type="number" name="barang[' + index + '][jumlah_pesan]" '
            + 'class="form-control form-control-sm jumlah" value="' + jumlah + '" min="1" required></td>'
            + '<td><input type="text" class="form-control form-control-sm subtotal" '
            + 'readonly value="' + formatRupiah(subtotal) + '"></td>'
            + '<td class="text-center"><button type="button" class="btn btn-sm btn-danger" '
            + 'onclick="hapusBaris(this)">✕</button></td>';

        tbody.appendChild(tr);
        index++;

        tr.querySelector('.select-barang').addEventListener('change', function() {
            var opt   = this.options[this.selectedIndex];
            var harga = parseInt(opt.dataset.harga) || 0;
            var jml   = parseInt(tr.querySelector('.jumlah').value) || 1;
            tr.querySelector('.harga-beli').value = formatRupiah(harga);
            tr.querySelector('.subtotal').value   = formatRupiah(harga * jml);
            hitungTotal();
        });

        tr.querySelector('.jumlah').addEventListener('input', function() {
            var sel   = tr.querySelector('.select-barang');
            var opt   = sel.options[sel.selectedIndex];
            var harga = parseInt(opt ? opt.dataset.harga : 0) || 0;
            tr.querySelector('.subtotal').value = formatRupiah(harga * parseInt(this.value || 0));
            hitungTotal();
        });

        hitungTotal();
    }

    function hapusBaris(btn) {
        btn.closest('tr').remove();
        hitungTotal();
    }

    function hitungTotal() {
        var total = 0;
        document.querySelectorAll('#bodyBarang .select-barang').forEach(function(sel) {
            var tr    = sel.closest('tr');
            var opt   = sel.options[sel.selectedIndex];
            var harga = parseInt(opt ? opt.dataset.harga : 0) || 0;
            var jml   = parseInt(tr.querySelector('.jumlah').value) || 0;
            total += harga * jml;
        });
        document.getElementById('totalLabel').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    function formatRupiah(angka) {
        return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (existingDetails.length > 0) {
            existingDetails.forEach(function(d) {
                tambahBaris(d.id_barang, d.jumlah_pesan);
            });
        } else {
            tambahBaris();
        }
    });
</script>
@endsection