<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('obats', function (Blueprint $table) {
            $table->id('id_obat');
            $table->string('nama_obat', 100);
            $table->string('jenis_obat', 50);
            $table->text('deskripsi_obat')->nullable();
            $table->decimal('harga_obat', 10, 2);
            $table->integer('stok_obat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obats');
    }
};
