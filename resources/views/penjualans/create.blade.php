@extends('layouts.app')
@section('title', 'Tambah Penjualan')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-primary"><i class="bi bi-plus-circle"></i> Tambah Penjualan</h5>
        <a href="{{ route('penjualan.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('penjualan.store') }}" method="POST">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Konsumen <span class="text-danger">*</span></label>
                    <select name="id_konsumen" class="form-select" required>
                        <option value="">-- Pilih Konsumen --</option>
                        @foreach($konsumens as $k)
                            <option value="{{ $k->id_konsumen }}"
                                {{ old('id_konsumen') == $k->id_konsumen ? 'selected' : '' }}>
                                {{ $k->nama_konsumen }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tanggal Penjualan <span class="text-danger">*</span></label>
                    <input type="date" name="tgl_penjualan" class="form-control"
                           value="{{ old('tgl_penjualan', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Jenis Pembayaran <span class="text-danger">*</span></label>
                    <select name="status_bayar" class="form-select" id="statusBayar" required>
                        <option value="">-- Pilih --</option>
                        <option value="cash"   {{ old('status_bayar') == 'cash'   ? 'selected' : '' }}>Cash</option>
                        <option value="credit" {{ old('status_bayar') == 'credit' ? 'selected' : '' }}>Credit</option>
                    </select>
                </div>
                <div class="col-md-4" id="jatuhTempoField" style="display:none">
                    <label class="form-label fw-bold">Jatuh Tempo <span class="text-danger">*</span></label>
                    <input type="date" name="jatuh_tempo" id="jatuhTempoInput" class="form-control"
                           value="{{ old('jatuh_tempo') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Dari Pemesanan (Opsional)</label>
                    <select name="id_pesan_konsumen" class="form-select">
                        <option value="">-- Tanpa referensi pemesanan --</option>
                        @foreach($pemesanans as $pm)
                            <option value="{{ $pm->id_pesan_konsumen }}"
                                {{ old('id_pesan_konsumen') == $pm->id_pesan_konsumen ? 'selected' : '' }}>
                                #{{ $pm->id_pesan_konsumen }} — {{ $pm->konsumen->nama_konsumen }}
                                ({{ \Carbon\Carbon::parse($pm->tgl_pesan)->format('d M Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="card border mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Detail Barang</span>
                    <button type="button" class="btn btn-sm btn-success" onclick="tambahBaris()">
                        <i class="bi bi-plus"></i> Tambah Barang
                    </button>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Barang</th>
                                <th width="120">Stok</th>
                                <th width="150">Harga Jual</th>
                                <th width="120">Jumlah</th>
                                <th width="160">Subtotal</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody id="bodyBarang"></tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="4" class="text-end fw-bold">Total</td>
                                <td class="fw-bold text-success" id="totalLabel">Rp 0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Penjualan
                </button>
                <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
    var daftarBarang = [];
    @foreach($barangs as $b)
    daftarBarang.push({
        id:           {{ $b->id_barang }},
        nama:         "{{ addslashes($b->nama_barang) }}",
        harga_satuan: {{ $b->harga_jual }},
        stok:         {{ $b->stok }},
        satuan:       "{{ addslashes($b->satuan) }}"
    });
    @endforeach

    var index = 0;

    function tambahBaris(idBarang, jumlah, hargaSatuan) {
        idBarang    = idBarang    || '';
        jumlah      = jumlah      || 1;
        hargaSatuan = hargaSatuan || 0;

        var tbody = document.getElementById('bodyBarang');
        var tr    = document.createElement('tr');

        var optionsHtml = '<option value="">-- Pilih --</option>';
        daftarBarang.forEach(function(b) {
            var selected = (b.id == idBarang) ? 'selected' : '';
            optionsHtml += '<option value="' + b.id + '" data-harga="' + b.harga_satuan
                         + '" data-stok="' + b.stok + '" ' + selected + '>'
                         + b.nama + ' (' + b.satuan + ')</option>';
        });

        if (!hargaSatuan && idBarang) {
            var found = daftarBarang.find(function(b) { return b.id == idBarang; });
            if (found) hargaSatuan = found.harga_satuan;
        }

        var stok = 0;
        if (idBarang) {
            var found = daftarBarang.find(function(b) { return b.id == idBarang; });
            if (found) stok = found.stok;
        }

        tr.innerHTML =
            '<td><select name="barang[' + index + '][id_barang]" class="form-select form-select-sm select-barang" required>'
            + optionsHtml + '</select></td>'
            + '<td><input type="text" class="form-control form-control-sm stok-info" readonly value="' + stok + '"></td>'
            + '<td><input type="number" name="barang[' + index + '][harga_satuan]" class="form-control form-control-sm harga-satuan" value="' + hargaSatuan + '" min="0" required></td>'
            + '<td><input type="number" name="barang[' + index + '][jumlah_jual]" class="form-control form-control-sm jumlah" value="' + jumlah + '" min="1" max="' + stok + '" required></td>'
            + '<td><input type="text" class="form-control form-control-sm subtotal" readonly value="' + formatRupiah(hargaSatuan * jumlah) + '"></td>'
            + '<td class="text-center"><button type="button" class="btn btn-sm btn-danger" onclick="hapusBaris(this)">✕</button></td>';

        tbody.appendChild(tr);
        index++;

        tr.querySelector('.select-barang').addEventListener('change', function() {
            var opt   = this.options[this.selectedIndex];
            var harga = parseInt(opt.dataset.harga) || 0;
            var stok  = parseInt(opt.dataset.stok)  || 0;
            tr.querySelector('.harga-satuan').value = harga;
            tr.querySelector('.stok-info').value    = stok;
            tr.querySelector('.jumlah').max         = stok;
            var jml = parseInt(tr.querySelector('.jumlah').value) || 1;
            tr.querySelector('.subtotal').value = formatRupiah(harga * jml);
            hitungTotal();
        });

        tr.querySelector('.harga-satuan').addEventListener('input', function() {
            var harga = parseInt(this.value) || 0;
            var jml   = parseInt(tr.querySelector('.jumlah').value) || 0;
            tr.querySelector('.subtotal').value = formatRupiah(harga * jml);
            hitungTotal();
        });

        tr.querySelector('.jumlah').addEventListener('input', function() {
            var harga = parseInt(tr.querySelector('.harga-satuan').value) || 0;
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
        document.querySelectorAll('#bodyBarang tr').forEach(function(tr) {
            var harga = parseInt(tr.querySelector('.harga-satuan').value) || 0;
            var jml   = parseInt(tr.querySelector('.jumlah').value) || 0;
            total += harga * jml;
        });
        document.getElementById('totalLabel').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    function formatRupiah(angka) {
        return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
    }

    document.getElementById('statusBayar').addEventListener('change', function() {
        var field = document.getElementById('jatuhTempoField');
        var input = document.getElementById('jatuhTempoInput');
        if (this.value === 'credit') {
            field.style.display = 'block';
            input.setAttribute('required', 'required');
        } else {
            field.style.display = 'none';
            input.removeAttribute('required');
            input.value = '';
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        tambahBaris();
        if (document.getElementById('statusBayar').value === 'credit') {
            document.getElementById('jatuhTempoField').style.display = 'block';
            document.getElementById('jatuhTempoInput').setAttribute('required', 'required');
        }
    });
</script>
@endsection