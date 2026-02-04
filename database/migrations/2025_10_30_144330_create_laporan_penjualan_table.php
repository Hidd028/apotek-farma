<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_penjualan', function (Blueprint $table) {
            $table->id();
            $table->date('periode_awal')->nullable();
            $table->date('periode_akhir')->nullable();
            $table->integer('total_transaksi')->default(0);
            $table->decimal('total_pendapatan', 15, 2)->default(0);
            $table->string('metode_terbanyak')->nullable();
            $table->string('kasir_terbanyak')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_penjualans');
    }
};
