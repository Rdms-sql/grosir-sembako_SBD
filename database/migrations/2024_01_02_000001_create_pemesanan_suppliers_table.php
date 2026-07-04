<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanan_suppliers', function (Blueprint $table) {
            $table->id('id_pesan_supplier');
            $table->foreignId('id_supplier')
                ->constrained('suppliers', 'id_supplier')
                ->onDelete('restrict');
            $table->foreignId('id_user')
                ->constrained('users', 'id_user')
                ->onDelete('restrict');
            $table->date('tgl_pesan');
            $table->enum('status', ['diajukan', 'diterima', 'batal'])->default('diajukan');
            $table->integer('total_pesan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemesanan_suppliers');
    }
};
