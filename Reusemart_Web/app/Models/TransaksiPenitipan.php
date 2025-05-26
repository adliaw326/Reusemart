<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPenitipan extends Model
{
    protected $table = 'transaksi_penitipan';
    protected $primaryKey = 'ID_PENITIPAN';
    public $incrementing = false; // Karena ID_PENITIPAN string kustom (misal TPN001)
    public $timestamps = false;

    protected $fillable = [
        'ID_PEGAWAI',
        'ID_PENITIPAN',
        'KODE_PRODUK',
        'ID_PENITIP',
        'TANGGAL_PENITIPAN',
        'STATUS_PENITIPAN',
        'TANGGAL_EXPIRED'
    ];

    protected $dates = [
        'TANGGAL_PENITIPAN',
    ];

    // Relasi dengan pegawai
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'ID_PEGAWAI', 'ID_PEGAWAI');
    }

    // Relasi dengan produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'KODE_PRODUK', 'KODE_PRODUK');
    }

    // Relasi dengan penitip (asumsi ada tabel penitip dengan ID_PENITIP)
    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'ID_PENITIP', 'ID_PENITIP');
    }
}
