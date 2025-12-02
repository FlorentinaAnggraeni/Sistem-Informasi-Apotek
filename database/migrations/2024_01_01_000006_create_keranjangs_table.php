<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keranjangs', function (Blueprint $table) {
            $table->id('id_keranjang');
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_obat');
            $table->integer('jumlah')->default(1);
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggans')->onDelete('cascade');
            $table->foreign('id_obat')->references('id_obat')->on('obats')->onDelete('cascade');

            // Unique constraint: satu pelanggan hanya punya 1 item obat yang sama di keranjang
            $table->unique(['id_pelanggan', 'id_obat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keranjangs');
    }
};
