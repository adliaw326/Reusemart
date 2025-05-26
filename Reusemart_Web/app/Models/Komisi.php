<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komisi extends Model
{
    protected $table = 'komisi';
    protected $primaryKey = 'ID_KOMISI';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'ID_KOMISI',
        'ID_PEMBELIAN',
        'ID_PENITIP',
        'ID_PEGAWAI',
        'JUMLAH_KOMISI',
    ];

    // Opsional: relasi jika diperlukan
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'ID_PEGAWAI', 'ID_PEGAWAI');
    }

    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'ID_PENITIP', 'ID_PENITIP');
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'ID_PEMBELIAN', 'ID_PEMBELIAN');
    }
}
