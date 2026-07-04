<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pesan_konsumens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pesan_konsumen')
                ->constrained('pemesanan_konsumens', 'id_pesan_konsumen')
                ->onDelete('cascade');
            $table->foreignId('id_barang')
                ->constrained('barangs', 'id_barang')
                ->onDelete('restrict');
            $table->integer('jumlah_pesan');
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesan_konsumens');
    }
};
