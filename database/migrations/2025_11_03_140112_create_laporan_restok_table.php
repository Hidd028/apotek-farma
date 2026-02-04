<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan_restok', function (Blueprint $table) {
            $table->id();
            $table->date('periode_awal');
            $table->date('periode_akhir');
            $table->integer('total_transaksi');
            $table->decimal('total_pengeluaran', 15, 2);
            $table->string('supplier_terbanyak')->nullable();
            $table->string('petugas_terbanyak')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_restok');
    }
};
