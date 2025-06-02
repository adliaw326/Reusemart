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
}
