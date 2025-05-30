<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    // Nama tabel jika tidak default (jamak dari model)
    protected $table = 'keranjang';

    // Primary key
    protected $primaryKey = 'ID_KERANJANG';

    // Jika primary key auto increment dan int
    public $incrementing = true;

    // Tipe primary key
    protected $keyType = 'int';

    // Timestamp di-disable karena kamu pakai manual tanggal_tambah
    public $timestamps = false;

    // Kolom yang bisa diisi massal (fill)
    protected $fillable = [
        'ID_PEMBELI',
        'KODE_PRODUK',
        'TANGGAL_TAMBAH',
    ];

    // Relasi ke pembeli (asumsi model Pembeli ada)
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'ID_PEMBELI', 'ID_PEMBELI');
    }

    // Relasi ke produk (asumsi model Produk ada)
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'KODE_PRODUK', 'KODE_PRODUK');
    }
}
