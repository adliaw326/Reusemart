<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Penitip extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'penitip'; // Nama tabel
    protected $primaryKey = 'ID_PENITIP'; // Primary key
    public $incrementing = false; // Karena primary key bukan auto-increment
    protected $keyType = 'string'; // Tipe primary key
    public $timestamps = false;

    protected $fillable = [
        'ID_PENITIP',
        'EMAIL_PENITIP',
        'PASSWORD_PENITIP',
        'NAMA_PENITIP',
        'NIK',
        'RATING_RATA_RATA_P',
        'saldo'
    ];
}
