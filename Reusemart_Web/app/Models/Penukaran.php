<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penukaran extends Model
{
    protected $table = 'penukaran';
    protected $primaryKey = 'ID_PENUKARAN';
    public $timestamps = false;

    protected $fillable = [
        'ID_PENUKARAN',
        'ID_PEMBELI',
        'ID_MERCHANDISE',
        'JUMLAH_PENUKARAN',
        'JUMLAH_HARGA_POIN',
        'TANGGAL_CLAIM_PENU',
        'TANGGAL_AMBIL_MERC',
    ];

    // Relasi opsional (jika dibutuhkan)
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'ID_PEMBELI');
    }

    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class, 'ID_MERCHANDISE');
    }
}
