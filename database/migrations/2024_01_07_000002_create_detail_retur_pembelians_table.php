<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_retur_pembelians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_retur_beli')
                ->constrained('retur_pembelians', 'id_retur_beli')
                ->onDelete('cascade');
            $table->foreignId('id_barang')
                ->constrained('barangs', 'id_barang')
                ->onDelete('restrict');
            $table->integer('jumlah_retur');
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_retur_pembelians');
    }
};
