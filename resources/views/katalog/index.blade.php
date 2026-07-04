@extends('layouts.konsumen')

@section('title', 'Katalog Produk')

@section('content')
<div class="container py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <h4 class="fw-bold mb-4">🛒 Katalog Produk Grosir Sembako</h4>

    <form action="{{ route('katalog.pesan') }}" method="POST">
        @csrf

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3 mb-4">
            @forelse($barangs as $b)
            <div class="col-md-4 col-sm-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="fw-bold">{{ $b->nama_barang }}</h6>
                        <p class="text-muted small mb-1">Satuan: {{ $b->satuan }}</p>
                        <p class="text-muted small mb-2">Stok: {{ $b->stok }}</p>
                        <p class="fw-bold text-success mb-3">
                            Rp {{ number_format($b->harga_jual, 0, ',', '.') }}
                        </p>
                        <div class="d-flex align-items-center gap-2">
                            <input type="hidden" name="barang[{{ $b->id_barang }}][id_barang]"
                                   value="{{ $b->id_barang }}">
                            <label class="form-label mb-0 small">Jumlah:</label>
                            <input type="number"
                                   name="barang[{{ $b->id_barang }}][jumlah_pesan]"
                                   class="form-control form-control-sm jumlah-input"
                                   data-harga="{{ $b->harga_jual }}"
                                   data-id="{{ $b->id_barang }}"
                                   value="0" min="0" max="{{ $b->stok }}"
                                   style="width: 80px">
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info">Belum ada produk tersedia saat ini.</div>
            </div>
            @endforelse
        </div>

        {{-- Ringkasan pesanan --}}
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted">Total Pesanan:</span>
                    <span class="fw-bold text-success fs-5 ms-2" id="totalPesanan">Rp 0</span>
                </div>
                <button type="submit" class="btn btn-primary btn-lg" id="btnPesan" disabled>
                    🛒 Buat Pesanan
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.querySelectorAll('.jumlah-input').forEach(function(input) {
        input.addEventListener('input', hitungTotal);
    });

    function hitungTotal() {
        var total = 0;
        var adaPesanan = false;

        document.querySelectorAll('.jumlah-input').forEach(function(input) {
            var jumlah = parseInt(input.value) || 0;
            var harga  = parseInt(input.dataset.harga) || 0;
            if (jumlah > 0) adaPesanan = true;
            total += jumlah * harga;
        });

        document.getElementById('totalPesanan').textContent =
            'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('btnPesan').disabled = !adaPesanan;
    }
</script>
@endsection