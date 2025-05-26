<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiskusiProduk extends Model
{
    protected $table = 'diskusi_produk';
    protected $primaryKey = 'ID_DISKUSI';
    public $incrementing = false; // Karena ID_DISKUSI bukan auto-increment
    public $timestamps = false; // Tidak ada created_at dan updated_at

    protected $fillable = [
        'KODE_PRODUK',
        'ID_DISKUSI',
        'ID_PEGAWAI',
        'ID_PEMBELI',
        'ISI_DISKUSI',
        'TANGGAL_POST',
        'ID_PARENT'
    ];

    // Relasi opsional (jika ada model terkait)
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'ID_PEGAWAI', 'ID_PEGAWAI');
    }

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'ID_PEMBELI', 'ID_PEMBELI');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'KODE_PRODUK', 'KODE_PRODUK');
    }

    public function children()
    {
        return $this->hasMany(DiskusiProduk::class, 'ID_PARENT');
    }
}
