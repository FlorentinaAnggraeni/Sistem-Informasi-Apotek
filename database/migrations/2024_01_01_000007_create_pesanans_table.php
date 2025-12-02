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

            $table->integer('total_nota')->default(0);
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status_pembayaran', ['pending', 'paid', 'failed'])->default('pending');
            $table->enum('status_pengiriman', ['pending', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->enum('status_penerimaan', ['belum diterima', 'diterima'])->default('belum diterima');
            $table->text('alamat_pengiriman')->nullable();
            $table->text('catatan')->nullable();
            $table->datetime('tanggal_pengiriman')->nullable();
            $table->string('no_resi', 100)->nullable();
            $table->timestamps();

            // Definisi foreign key
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggans')->onDelete('cascade');
            $table->foreign('id_karyawan')->references('id_karyawan')->on('karyawans')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
