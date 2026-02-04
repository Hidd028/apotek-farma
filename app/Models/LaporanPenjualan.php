<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LaporanPenjualan extends Model
{
    protected $table = 'laporan_penjualan';

    // Aktifkan timestamps hanya sebagian
    public $timestamps = true;
    const UPDATED_AT = null; // 🚫 Tidak pakai updated_at, hanya created_at

    protected $fillable = [
        'periode_awal',
        'periode_akhir',
        'total_transaksi',
        'total_pendapatan',
        'metode_terbanyak',
        'kasir_terbanyak',
    ];

    // Otomatis ubah ke Carbon
    protected $casts = [
        'periode_awal' => 'date',
        'periode_akhir' => 'date',
        'created_at' => 'datetime',
    ];

    // Format tanggal untuk tampilan
    public function getPeriodeAwalFormattedAttribute()
    {
        return $this->periode_awal ? $this->periode_awal->format('d/m/Y') : '-';
    }

    public function getPeriodeAkhirFormattedAttribute()
    {
        return $this->periode_akhir ? $this->periode_akhir->format('d/m/Y') : '-';
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at ? $this->created_at->format('d/m/Y H:i') : '-';
    }
}