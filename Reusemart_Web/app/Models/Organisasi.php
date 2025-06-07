<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Organisasi extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'organisasi';
    public $timestamps = false;
    protected $primaryKey = 'ID_ORGANISASI';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'NAMA_ORGANISASI',
        'EMAIL_ORGANISASI',
        'PASSWORD_ORGANISASI',
        'fcm_token',
    ];

    protected $hidden = ['PASSWORD_ORGANISASI'];

    public function getAuthPassword()
    {
        return $this->PASSWORD_ORGANISASI;
    }
    public function donasi()
    {
        return $this->hasMany(Donasi::class, 'ID_ORGANISASI', 'ID_ORGANISASI');
    }

    public function alamat()
    {
        return $this->hasMany(Alamat::class, 'ID_ORGANISASI', 'ID_ORGANISASI');
    }

    public function alamatDefault()
    {
        return $this->hasOne(Alamat::class, 'ID_ORGANISASI', 'ID_ORGANISASI')
                    ->where('STATUS_DEFAULT', 1);
    }


}
