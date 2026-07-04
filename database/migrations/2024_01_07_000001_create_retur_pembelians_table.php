<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retur_pembelians', function (Blueprint $table) {
            $table->id('id_retur_beli');
            $table->foreignId('id_pembelian')
                ->constrained('pembelians', 'id_pembelian')
                ->onDelete('restrict');
            $table->foreignId('id_user')
                ->constrained('users', 'id_user')
                ->onDelete('restrict');
            $table->date('tgl_retur');
            $table->integer('total_retur');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retur_pembelians');
    }
};
