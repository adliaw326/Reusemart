<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestModel extends Model
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

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'KODE_PRODUK');
    }

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'ID_ORGANISASI');
    }
}
