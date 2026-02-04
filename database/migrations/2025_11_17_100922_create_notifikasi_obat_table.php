<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('notifikasi_obat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('obat_id');
            $table->string('pesan');
            $table->boolean('dibaca')->default(false);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi_obat');
    }
};
