<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePegawai extends Model
{
    protected $table = 'role_pegawai';
    protected $primaryKey = 'ID_ROLE';
    public $incrementing = false; // Karena ID_ROLE bertipe string (misal RL001)
    public $timestamps = false;

    protected $fillable = [
        'ID_ROLE',
        'NAMA_ROLE',
    ];

    // Jika ada relasi ke Pegawai, bisa ditambahkan (optional)
    public function pegawais()
    {
        return $this->hasMany(Pegawai::class, 'ID_ROLE', 'ID_ROLE');
    }
}
