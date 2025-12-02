<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->enum('metode_pembayaran', ['transfer', 'e-wallet', 'qris'])->nullable()->after('total_nota');
            $table->string('payment_code', 50)->nullable()->after('metode_pembayaran');
            $table->string('va_number', 50)->nullable()->after('payment_code');
            $table->text('qr_code')->nullable()->after('va_number');
        });
    }

    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn(['metode_pembayaran', 'payment_code', 'va_number', 'qr_code']);
        });
    }
};
