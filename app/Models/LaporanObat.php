<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanObat extends Model
{
    use HasFactory;

    protected $table = 'laporan_obat';

    protected $fillable = [
        'periode_awal',
        'periode_akhir',
        'nama_obat',
        'kategori',
        'stok_awal',
        'jumlah_masuk',
        'jumlah_keluar',
        'stok_akhir',
        'harga_jual',
        'total_nilai',
    ];
}
