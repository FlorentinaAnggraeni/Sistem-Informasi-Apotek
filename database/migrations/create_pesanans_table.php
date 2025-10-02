<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id('id_pesanan');

            // Foreign keys
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_karyawan')->nullable();
            $table->unsignedBigInteger('id_obat');

            $table->integer('total_nota')->default(0);
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status_pembayaran', ['pending', 'paid', 'failed'])->default('pending');
            $table->enum('status_pengiriman', ['pending', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->enum('status_penerimaan', ['belum diterima', 'diterima'])->default('belum diterima');
            $table->timestamps();

            // Definisi foreign key
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggans')->onDelete('cascade');
            $table->foreign('id_karyawan')->references('id_karyawan')->on('karyawans')->onDelete('set null');
            $table->foreign('id_obat')->references('id_obat')->on('obats')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
