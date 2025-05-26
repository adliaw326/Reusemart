<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    protected $table = 'donasi';
    protected $primaryKey = 'ID_DONASI';
    public $incrementing = false; // ID_DONASI tidak auto-increment
    public $timestamps = false;   // Tidak ada kolom created_at / updated_at

    protected $fillable = [
        'KODE_PRODUK',
        'ID_DONASI',
        'ID_ORGANISASI',
        'ID_REQUEST',
        'TANGGAL_DONASI',
        'NAMA_PENERIMA',
    ];

    // Relasi opsional
    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'ID_ORGANISASI', 'ID_ORGANISASI');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'KODE_PRODUK', 'KODE_PRODUK');
    }

    public function request()
    {
        return $this->belongsTo(RequestDonasi::class, 'ID_REQUEST', 'ID_REQUEST');
    }
}
