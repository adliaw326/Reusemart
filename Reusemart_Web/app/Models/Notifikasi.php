<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';
    protected $primaryKey = 'ID_NOTIFIKASI';
    public $timestamps = false;
    protected $fillable = [
        'ID_PEMBELI', 'ID_PENITIP', 'ID_PEGAWAI', 'ISI', 'TANGGAL', 'JUDUl'
    ];

    // Relasi ke Pembeli
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'ID_PEMBELI');
    }

    // Relasi ke Penitip
    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'ID_PENITIP');
    }

    // Relasi ke Pegawai
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'ID_PEGAWAI');
    }

    public static function createNotifikasi(array $data)
    {
        // Optional: validasi minimal satu ID role diisi
        if (
            empty($data['ID_PEMBELI']) &&
            empty($data['ID_PENITIP']) &&
            empty($data['ID_PEGAWAI'])
        ) {
            throw new \InvalidArgumentException('Minimal salah satu ID_PEMBELI, ID_PENITIP, atau ID_PEGAWAI harus diisi.');
        }

        return self::create([
            'ID_PEMBELI' => $data['ID_PEMBELI'] ?? null,
            'ID_PENITIP' => $data['ID_PENITIP'] ?? null,
            'ID_PEGAWAI' => $data['ID_PEGAWAI'] ?? null,
            'ISI' => $data['ISI'],
            'TANGGAL' => $data['TANGGAL'],
            'JUDUL' => $data['JUDUL'],
        ]);
    }
}
