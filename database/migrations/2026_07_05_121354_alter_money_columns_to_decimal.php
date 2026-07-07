<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->decimal('harga_beli', 15, 2)->change();
            $table->decimal('harga_jual', 15, 2)->change();
        });

        Schema::table('konsumens', function (Blueprint $table) {
            $table->decimal('limit_kredit', 15, 2)->change();
        });

        Schema::table('piutangs', function (Blueprint $table) {
            $table->decimal('total_piutang', 15, 2)->change();
            $table->decimal('sisa_piutang', 15, 2)->change();
        });

        Schema::table('penerimaan_piutangs', function (Blueprint $table) {
            $table->decimal('jumlah_terima', 15, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->integer('harga_beli')->change();
            $table->integer('harga_jual')->change();
        });

        Schema::table('konsumens', function (Blueprint $table) {
            $table->integer('limit_kredit')->change();
        });

        Schema::table('piutangs', function (Blueprint $table) {
            $table->integer('total_piutang')->change();
            $table->integer('sisa_piutang')->change();
        });

        Schema::table('penerimaan_piutangs', function (Blueprint $table) {
            $table->integer('jumlah_terima')->change();
        });
    }
};
