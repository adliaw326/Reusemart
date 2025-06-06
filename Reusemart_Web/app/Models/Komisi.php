<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komisi extends Model
{
    protected $table = 'komisi';
    protected $primaryKey = 'ID_KOMISI';
    public $timestamps = false;

    protected $fillable = [
        'ID_PEMBELIAN',
        'KODE_PRODUK',
        'KOMISI_REUSEMART',
        'KOMISI_PENITIP',
        'BONUS_PENITIP',
        'JUMLAH_KOMISI',
        'KOMISI_HUNTER',
    ];

    // Opsional: relasi jika diperlukan
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'KODE_PRODUK', 'KODE_PRODUK');
    }


    public function pembelian()
    {
        return $this->belongsTo(TransaksiPembelian::class, 'ID_PEMBELIAN', 'ID_PEMBELIAN');
    }
}
