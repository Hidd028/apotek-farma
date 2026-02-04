<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanRestok extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'laporan_restok';

    // Kolom yang dapat diisi (fillable)
    protected $fillable = [
        'periode_awal',
        'periode_akhir',
        'total_transaksi',
        'total_pengeluaran',
        'supplier_terbanyak',
        'petugas_terbanyak',
    ];

    // Tipe data yang perlu dikonversi otomatis
    protected $casts = [
        'periode_awal' => 'date',
        'periode_akhir' => 'date',
        'total_pengeluaran' => 'decimal:2',
    ];

    // Jika ingin relasi ke tabel restok_obats (jika diperlukan)
    public function restokObats()
    {
        return $this->hasMany(RestokObat::class, 'laporan_restok_id', 'id');
    }
}