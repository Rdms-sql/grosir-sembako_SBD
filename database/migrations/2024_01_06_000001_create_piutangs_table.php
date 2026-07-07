<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('piutangs', function (Blueprint $table) {
            $table->id('id_piutang');
            $table->foreignId('id_penjualan')
                ->unique()
                ->constrained('penjualans', 'id_penjualan')
                ->onDelete('restrict');
            $table->foreignId('id_konsumen')
                ->constrained('konsumens', 'id_konsumen')
                ->onDelete('restrict');
            $table->decimal('total_piutang', 15, 2);
            $table->decimal('sisa_piutang', 15, 2);
            $table->date('jatuh_tempo');
            $table->enum('status', ['belum_lunas', 'lunas'])->default('belum_lunas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('piutangs');
    }
};
