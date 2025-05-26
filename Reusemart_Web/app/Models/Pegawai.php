<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\RolePegawai;

class Pegawai extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'pegawai';
    protected $primaryKey = 'ID_PEGAWAI';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'ID_PEGAWAI',
        'ID_ROLE',
        'NAMA_PEGAWAI',
        'EMAIL_PEGAWAI',
        'PASSWORD_PEGAWAI',
        'TANGGAL_LAHIR',
    ];

    public function role()
    {
        return $this->belongsTo(RolePegawai::class, 'ID_ROLE');
    }
}
