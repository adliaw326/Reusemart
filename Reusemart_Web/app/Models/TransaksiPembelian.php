<?php

namespace App\Models;
use App\Models\Pembeli;
use App\Models\Produk;

use Illuminate\Database\Eloquent\Model;

class TransaksiPembelian extends Model
{
    protected $table = 'transaksi_pembelian'; // Nama tabel di database
    protected $primaryKey = 'ID_PEMBELIAN';
    public $timestamps = false;
    protected $fillable = [
        'ID_PEMBELI',
        'ID_PEGAWAI',
        'ID_ALAMAT',
        'STATUS_TRANSAKSI',
        'TANGGAL_PESAN',
        'TANGGAL_LUNAS',
        'TANGGAL_KIRIM',
        'TANGGAL_SAMPAI',
        'TANGGAL_AMBIL',
        'STATUS_RATING',
        'STATUS_PENGIRIMAN',
        'BUKTI_BAYAR',
        'TOTAL_BAYAR',
        'POIN_DISKON'
    ];

    protected $dates = [
        'TANGGAL_PESAN',
        'TANGGAL_LUNAS',
        'TANGGAL_KIRIM',
        'TANGGAL_AMBIL',
    ];

    // Relasi ke Pembeli
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'ID_PEMBELI', 'ID_PEMBELI');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'ID_PEMBELIAN', 'ID_PEMBELIAN');
    }

     public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'ID_PEGAWAI', 'ID_PEGAWAI');
    }

    public function alamat()
    {
        return $this->belongsTo(Alamat::class, 'ID_ALAMAT', 'ID_ALAMAT');

    }
}
