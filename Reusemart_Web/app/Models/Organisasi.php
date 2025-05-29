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
        'ID_ORGANISASI',
        'NAMA_ORGANISASI',
        'EMAIL_ORGANISASI',
        'PASSWORD_ORGANISASI',
    ];

    protected $hidden = ['PASSWORD_ORGANISASI'];

    public function getAuthPassword()
    {
        return $this->PASSWORD_ORGANISASI;
    }
}
