<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    protected $table = 'kategori_produk';
    protected $primaryKey = 'ID_KATEGORI';
    public $incrementing = false; // Karena ID_KATEGORI berupa string seperti KT001
    public $timestamps = false;   // Tidak ada created_at dan updated_at

    protected $fillable = [
        'ID_KATEGORI',
        'NAMA_KATEGORI',
    ];

    // Relasi ke produk jika ada (opsional)
    public function produk()
    {
        return $this->hasMany(Produk::class, 'ID_KATEGORI', 'ID_KATEGORI');
    }
}
