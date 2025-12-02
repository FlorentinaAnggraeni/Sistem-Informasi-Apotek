<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reseps', function (Blueprint $table) {
            $table->id('id_resep');
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_apoteker')->nullable();
            $table->string('no_resep', 50)->unique();
            $table->string('nama_dokter', 100);
            $table->string('nama_pasien', 100);
            $table->text('diagnosa')->nullable();
            $table->string('foto_resep')->nullable();
            $table->enum('status', ['pending', 'diproses', 'selesai', 'ditolak'])->default('pending');
            $table->text('catatan_apoteker')->nullable();
            $table->date('tanggal_resep');
            $table->timestamps();

            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggans')->onDelete('cascade');
            $table->foreign('id_apoteker')->references('id_karyawan')->on('karyawans')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reseps');
    }
};
