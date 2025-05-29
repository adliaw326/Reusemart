<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Penitip extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'penitip'; // Nama tabel
    protected $primaryKey = 'ID_PENITIP'; // Primary key
    public $timestamps = false;

    protected $fillable = [
        'EMAIL_PENITIP',
        'PASSWORD_PENITIP',
        'NAMA_PENITIP',
        'NIK',
        'NO_TELP_PENITIP',
        'RATING_RATA_RATA_P',
        'SALDO_PENITIP',
        'POIN_PENITIP',
    ];

    protected $hidden = ['PASSWORD_PENITIP'];

    public function getAuthPassword()
    {
        return $this->PASSWORD_PENITIP;
    }

    public function alamatDefault()
    {
        return $this->hasOne(Alamat::class, 'ID_PENITIP', 'ID_PENITIP')
                    ->where('STATUS_DEFAULT', 1);
    }
}
