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
        Schema::create('laporan_obat', function (Blueprint $table) {
            $table->id();
            $table->date('periode_awal')->nullable();
            $table->date('periode_akhir')->nullable();
            $table->string('nama_obat');
            $table->string('kategori')->nullable();
            $table->integer('stok_awal')->default(0);
            $table->integer('jumlah_masuk')->default(0);
            $table->integer('jumlah_keluar')->default(0);
            $table->integer('stok_akhir')->default(0);
            $table->decimal('harga_jual', 12, 2)->default(0);
            $table->decimal('total_nilai', 15, 2)->default(0);
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_obat');
    }
};
