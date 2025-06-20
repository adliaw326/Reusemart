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
    public $timestamps = false;

    protected $fillable = [
        'ID_ROLE',
        'NAMA_PEGAWAI',
        'EMAIL_PEGAWAI',
        'PASSWORD_PEGAWAI',
        'TANGGAL_LAHIR',
        'fcm_token',
    ];

    public function role()
    {
        return $this->belongsTo(RolePegawai::class, 'ID_ROLE');
    }

    protected $hidden = ['PASSWORD_PEGAWAI'];

    public function getAuthPassword()
    {
        return $this->PASSWORD_PEGAWAI;
    }

    public function produk2()
    {
        return $this->hasMany(Pegawai::class, 'ID_HUNTER');
    }
}
