<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id('id_karyawan');
            $table->unsignedBigInteger('id_user');
            $table->string('nama_karyawan', 100);
            $table->string('jabatan', 50);
            $table->string('no_telp_karyawan', 20)->nullable();
            $table->text('alamat_karyawan')->nullable();
            $table->date('tanggal_bergabung')->nullable();
            $table->decimal('gaji', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
