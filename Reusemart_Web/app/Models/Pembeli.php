<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Pembeli extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'pembeli';
    public $timestamps = false;
    protected $primaryKey = 'ID_PEMBELI';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ID_PEMBELI',
        'EMAIL_PEMBELI',
        'PASSWORD_PEMBELI',
        'NAMA_PEMBELI',
        'POIN_PEMBELI'
    ];
    protected $hidden = ['PASSWORD_PEMBELI']; // Sembunyikan password saat JSON response
}
