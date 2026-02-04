<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotifikasiObat extends Model
{
    protected $table = 'notifikasi_obat';
    protected $fillable = ['obat_id', 'pesan', 'dibaca'];

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}
