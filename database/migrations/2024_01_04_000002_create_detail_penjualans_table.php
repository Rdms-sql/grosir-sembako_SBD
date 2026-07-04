<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_penjualan')
                ->constrained('penjualans', 'id_penjualan')
                ->onDelete('cascade');
            $table->foreignId('id_barang')
                ->constrained('barangs', 'id_barang')
                ->onDelete('restrict');
            $table->integer('jumlah_jual');
            $table->integer('harga_satuan'); // snapshot harga_jual saat transaksi
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penjualans');
    }
};
