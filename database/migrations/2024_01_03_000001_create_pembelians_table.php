<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id('id_pembelian');
            $table->foreignId('id_supplier')
                ->constrained('suppliers', 'id_supplier')
                ->onDelete('restrict');
            $table->foreignId('id_user')
                ->constrained('users', 'id_user')
                ->onDelete('restrict');
            $table->foreignId('id_pesan_supplier')
                ->nullable()
                ->constrained('pemesanan_suppliers', 'id_pesan_supplier')
                ->onDelete('set null');
            $table->date('tgl_pembelian');
            $table->enum('status_bayar', ['cash', 'credit']);
            $table->integer('total_beli');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};
