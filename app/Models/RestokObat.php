<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestokObat extends Model
{
    use HasFactory;

    protected $fillable = [
        'obat_id',
        'supplier_id',
        'jumlah',
        'harga_beli',
        'tanggal_masuk',
        'user_id',
    ];

    protected $casts = [
        'tanggal_masuk' => 'datetime',
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}