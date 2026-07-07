<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penerimaan_piutangs', function (Blueprint $table) {
            $table->id('id_terima_piutang');
            $table->foreignId('id_piutang')
                ->constrained('piutangs', 'id_piutang')
                ->onDelete('cascade');
            $table->foreignId('id_user')
                ->constrained('users', 'id_user')
                ->onDelete('restrict');
            $table->date('tgl_terima');
            $table->decimal('jumlah_terima', 15, 2);
            $table->enum('metode_bayar', ['tunai', 'transfer'])->default('tunai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penerimaan_piutangs');
    }
};
