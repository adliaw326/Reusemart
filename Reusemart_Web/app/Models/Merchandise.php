<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchandise extends Model
{
    protected $table = 'merchandise';
    protected $primaryKey = 'ID_MERCHANDISE';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'ID_MERCHANDISE',
        'NAMA_MERCHANDISE',
        'HARGA_POIN',
        'JUMLAH_MERCH'
    ];
}
