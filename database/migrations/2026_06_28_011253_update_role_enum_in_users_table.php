<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menggunakan method bawaan Laravel untuk mengubah isi ENUM
            $table->enum('role', ['admin', 'kasir', 'konsumen'])->default('kasir')->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Mengembalikan ke kondisi semula saat di-rollback
            $table->enum('role', ['admin', 'kasir'])->default('kasir')->change();
        });
    }
};