<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konsumens', function (Blueprint $table) {
            $table->id('id_konsumen');
            $table->string('nama_konsumen', 100);
            $table->string('no_hp', 15)->nullable();
            $table->text('alamat')->nullable();
            $table->decimal('limit_kredit', 15, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konsumens');
    }
};
