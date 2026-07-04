<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_hutangs', function (Blueprint $table) {
            $table->id('id_bayar_hutang');
            $table->foreignId('id_hutang')
                ->constrained('hutangs', 'id_hutang')
                ->onDelete('cascade');
            $table->foreignId('id_user')
                ->constrained('users', 'id_user')
                ->onDelete('restrict');
            $table->date('tgl_bayar');
            $table->integer('jumlah_bayar');
            $table->enum('metode_bayar', ['tunai', 'transfer'])->default('tunai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_hutangs');
    }
};
