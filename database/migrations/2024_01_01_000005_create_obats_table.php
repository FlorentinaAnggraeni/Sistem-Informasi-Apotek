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
            $table->unsignedBigInteger('id_kategori')->nullable();
            $table->unsignedBigInteger('id_supplier')->nullable();
            $table->string('nama_obat', 100);
            $table->string('jenis_obat', 50);
            $table->text('deskripsi_obat')->nullable();
            $table->decimal('harga_obat', 10, 2);
            $table->integer('stok_obat');
            $table->string('gambar_obat')->nullable();
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->string('no_batch', 50)->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_kategori')->references('id_kategori')->on('kategoris')->onDelete('set null');
            $table->foreign('id_supplier')->references('id_supplier')->on('suppliers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obats');
    }
};
