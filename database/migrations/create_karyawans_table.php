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
    $table->string('nama', 100);
    $table->string('alamat', 255);
    $table->string('username', 50)->unique();
    $table->string('password');
    $table->enum('role', ['karyawan', 'apoteker'])->default('karyawan');
    $table->timestamps();
});
