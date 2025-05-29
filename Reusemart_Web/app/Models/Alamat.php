<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $table = 'alamat'; // Nama tabel di database
    protected $primaryKey = 'ID_ALAMAT'; // Primary key
    public $incrementing = false; // Karena ID_ALAMAT bukan auto-increment
    public $timestamps = false; // Tidak ada kolom created_at / updated_at

    protected $fillable = [
        'ID_PEMBELI',
        'ID_ORGANISASI',
        'ID_PENITIP',
        'ID_ALAMAT',
        'LOKASI',
        'STATUS_DEFAULT',
    ];

        public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'ID_PEMBELI', 'ID_PEMBELI');
    }

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'ID_ORGANISASI', 'ID_ORGANISASI');
    }

}
