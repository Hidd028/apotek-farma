<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obats';

    protected $fillable = [
        'nama_obat',
        'kategori',
        'stok',
        'satuan',
        'harga',
        'tanggal_kadaluarsa',
        'gambar',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function restoks()
    {
        return $this->hasMany(RestokObat::class, 'obat_id');
    }

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class, 'obat_id');
    }
}
