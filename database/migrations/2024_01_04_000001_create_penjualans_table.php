<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id('id_penjualan');
            $table->foreignId('id_konsumen')
                ->constrained('konsumens', 'id_konsumen')
                ->onDelete('restrict');
            $table->foreignId('id_user')
                ->constrained('users', 'id_user')
                ->onDelete('restrict');
            $table->foreignId('id_pesan_konsumen')
                ->nullable()
                ->constrained('pemesanan_konsumens', 'id_pesan_konsumen')
                ->onDelete('set null');
            $table->date('tgl_penjualan');
            $table->enum('status_bayar', ['cash', 'credit']);
            $table->integer('total_jual');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
