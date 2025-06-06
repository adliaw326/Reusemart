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

    public static function tambahProduk($userId, $kodeProduk)
    {
        $exists = self::where('ID_PEMBELI', $userId)
                      ->where('KODE_PRODUK', $kodeProduk)
                      ->exists();

        if ($exists) {
            return [
                'success' => false,
                'message' => 'Produk sudah ada di keranjang',
                'code' => 409
            ];
        }

        $keranjang = self::create([
            'ID_PEMBELI' => $userId,
            'KODE_PRODUK' => $kodeProduk,
            'TANGGAL_TAMBAH' => now()
        ]);

        return [
            'success' => true,
            'data' => $keranjang,
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'code' => 201
        ];
    }

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
