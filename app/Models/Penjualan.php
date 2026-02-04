<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $fillable = [
        'tanggal_transaksi',
        'nama_user',
        'metode_pembayaran',
        'total_harga'
    ];

    public function details()
    {
        return $this->hasMany(PenjualanDetail::class, 'penjualan_id');
    }
}