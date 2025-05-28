<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $table = 'request';
    protected $primaryKey = 'ID_REQUEST';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'ID_ORGANISASI',
        'ID_REQUEST',
        'KODE_PRODUK',
        'DETAIL_REQUEST',
        'STATUS_REQUEST',
    ];

    // Define the relationship with Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'KODE_PRODUK', 'KODE_PRODUK');
    }

    // Define the relationship with Organisasi
    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'ID_ORGANISASI', 'ID_ORGANISASI');
    }
}
