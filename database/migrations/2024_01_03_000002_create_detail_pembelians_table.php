<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pembelians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pembelian')
                ->constrained('pembelians', 'id_pembelian')
                ->onDelete('cascade');
            $table->foreignId('id_barang')
                ->constrained('barangs', 'id_barang')
                ->onDelete('restrict');
            $table->integer('jumlah_beli');
            $table->integer('harga_satuan'); // snapshot harga_beli saat transaksi
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pembelians');
    }
};
