<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_pesanan');
            $table->unsignedBigInteger('id_karyawan')->nullable();
            $table->datetime('tanggal_transaksi')->default(now());
            $table->decimal('total_transaksi', 10, 2);
            $table->enum('metode_pembayaran', ['cash', 'transfer', 'e-wallet', 'qris'])->default('cash');
            $table->enum('status_transaksi', ['pending', 'success', 'failed'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanans')->onDelete('cascade');
            $table->foreign('id_karyawan')->references('id_karyawan')->on('karyawans')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
