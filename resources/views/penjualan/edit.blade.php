@extends('layouts.app')

@section('title', 'Edit Penjualan')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-cash-stack text-success"></i>
            Edit Transaksi PJ-{{ str_pad($penjualan->id_penjualan, 5, '0', STR_PAD_LEFT) }}
        </h4>
        <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('penjualan.update', $penjualan->id_penjualan) }}" method="POST" id="formPenjualan">
        @csrf
        @method('PUT')

        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Konsumen</label>
                        <select name="id_konsumen" class="form-select" required>
                            <option value="">-- Pilih Konsumen --</option>
                            @foreach($konsumens as $k)
                                <option value="{{ $k->id_konsumen }}"
                                    {{ old('id_konsumen', $penjualan->id_konsumen) == $k->id_konsumen ? 'selected' : '' }}>
                                    {{ $k->nama_konsumen }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tanggal Penjualan</label>
                        <input type="date" name="tgl_penjualan" class="form-control"
                               value="{{ old('tgl_penjualan', \Carbon\Carbon::parse($penjualan->tgl_penjualan)->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Status Bayar</label>
                        <select name="status_bayar" class="form-select" required>
                            <option value="cash" {{ old('status_bayar', $penjualan->status_bayar) == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="credit" {{ old('status_bayar', $penjualan->status_bayar) == 'credit' ? 'selected' : '' }}>Credit</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Item Barang</span>
                <button type="button" class="btn btn-sm btn-primary" onclick="tambahBaris()">
                    <i class="bi bi-plus-lg"></i> Tambah Item
                </button>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0" id="tabelItem">
                    <thead style="background:#1e293b; color:#fff;">
                        <tr>
                            <th style="width:35%">Barang</th>
                            <th style="width:15%">Stok</th>
                            <th style="width:15%">Harga Jual</th>
                            <th style="width:15%">Jumlah</th>
                            <th style="width:15%">Subtotal</th>
                            <th style="width:5%"></th>
                        </tr>
                    </thead>
                    <tbody id="bodyItem">
                        {{-- diisi via JS dari data lama --}}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-semibold">Total</td>
                            <td class="fw-semibold" id="grandTotalText">Rp 0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="mt-3 d-flex justify-content-end gap-2">
            <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Update Transaksi
            </button>
        </div>
    </form>

    <script>
        const daftarBarang = @json($barangs);

        // Item lama dari transaksi yang sedang diedit
        const itemLama = @json($penjualan->detailPenjualans);

        let rowIndex = 0;

        function buatOptions(selectedId = null) {
            let options = '<option value="">-- Pilih Barang --</option>';
            daftarBarang.forEach(b => {
                const selected = (selectedId && b.id_barang == selectedId) ? 'selected' : '';
                options += `<option value="${b.id_barang}" data-stok="${b.stok}" data-harga="${b.harga_jual}" ${selected}>${b.nama_barang}</option>`;
            });
            return options;
        }

        function tambahBaris(itemAwal = null) {
            const tbody = document.getElementById('bodyItem');
            const tr = document.createElement('tr');
            tr.dataset.index = rowIndex;

            const options = buatOptions(itemAwal ? itemAwal.id_barang : null);
            const jumlahAwal = itemAwal ? itemAwal.jumlah_jual : 1;

            tr.innerHTML = `
                <td>
                    <select name="items[${rowIndex}][id_barang]" class="form-select form-select-sm barang-select" required onchange="updateBaris(this)">
                        ${options}
                    </select>
                </td>
                <td class="text-center stok-cell">-</td>
                <td class="text-end harga-cell">-</td>
                <td>
                    <input type="number" name="items[${rowIndex}][jumlah_jual]" class="form-control form-control-sm jumlah-input"
                           min="1" value="${jumlahAwal}" required oninput="hitungSubtotal(this)">
                </td>
                <td class="text-end subtotal-cell">Rp 0</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusBaris(this)">
                        <i class="bi bi-x"></i>
                    </button>
                </td>
            `;

            tbody.appendChild(tr);
            rowIndex++;

            if (itemAwal) {
                updateBaris(tr.querySelector('.barang-select'));
            }
        }

        function updateBaris(select) {
            const tr = select.closest('tr');
            const opt = select.options[select.selectedIndex];
            const stok = opt.dataset.stok ?? 0;
            const harga = opt.dataset.harga ?? 0;

            tr.querySelector('.stok-cell').textContent = opt.value ? stok : '-';
            tr.querySelector('.harga-cell').textContent = opt.value ? 'Rp ' + Number(harga).toLocaleString('id-ID') : '-';

            hitungSubtotal(tr.querySelector('.jumlah-input'));
        }

        function hitungSubtotal(input) {
            const tr = input.closest('tr');
            const select = tr.querySelector('.barang-select');
            const opt = select.options[select.selectedIndex];
            const harga = Number(opt.dataset.harga ?? 0);
            const jumlah = Number(input.value ?? 0);
            const subtotal = harga * jumlah;

            tr.querySelector('.subtotal-cell').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            hitungGrandTotal();
        }

        function hitungGrandTotal() {
            let total = 0;
            document.querySelectorAll('#bodyItem tr').forEach(tr => {
                const select = tr.querySelector('.barang-select');
                const input = tr.querySelector('.jumlah-input');
                const opt = select.options[select.selectedIndex];
                const harga = Number(opt.dataset.harga ?? 0);
                const jumlah = Number(input.value ?? 0);
                total += harga * jumlah;
            });
            document.getElementById('grandTotalText').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        function hapusBaris(btn) {
            btn.closest('tr').remove();
            hitungGrandTotal();
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (itemLama.length > 0) {
                itemLama.forEach(item => tambahBaris(item));
            } else {
                tambahBaris();
            }
        });

        document.getElementById('formPenjualan').addEventListener('submit', function (e) {
            const rows = document.querySelectorAll('#bodyItem tr');
            if (rows.length === 0) {
                e.preventDefault();
                alert('Tambahkan minimal 1 item barang.');
            }
        });
    </script>

@endsection