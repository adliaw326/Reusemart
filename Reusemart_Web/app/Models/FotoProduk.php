<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoProduk extends Model
{
    protected $table = 'foto_produk';
    protected $primaryKey = 'ID_FOTO';
    public $timestamps = false;

    protected $fillable = [
        'KODE_PRODUK',
        'PATH_PRODUK'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'KODE_PRODUK', 'KODE_PRODUK');
    }
}
