<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemiliks', function (Blueprint $table) {
            $table->id('id_pemilik');
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('no_telpon', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemiliks');
    }
};
