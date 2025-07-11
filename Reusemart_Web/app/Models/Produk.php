<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pegawai;
use App\Models\Pembeli;
use App\Models\TransaksiPembelian;
use App\Models\KategoriProduk;

class Produk extends Model
{

    use HasFactory;
    protected $table = 'produk';
    protected $primaryKey = 'KODE_PRODUK';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'KODE_PRODUK',
        'ID_PEGAWAI',
        'ID_PEMBELI',
        'ID_PEMBELIAN',
        'ID_KATEGORI',
        'NAMA_PRODUK',
        'KATEGORI',
        'BERAT',
        'HARGA',
        'GARANSI',
        'RATING',
        'ID_HUNTER',
    ];

    // Relasi opsional jika ada model terkait
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'ID_PEGAWAI');
    }

    public function pegawai2()
    {
        return $this->belongsTo(Pegawai::class, 'ID_HUNTER');
    }

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'ID_PEMBELI');
    }

    public function pembelian()
    {
        return $this->belongsTo(TransaksiPembelian::class, 'ID_PEMBELIAN');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'ID_KATEGORI');
    }

    public function transaksiPenitipan()
    {
        return $this->hasOne(TransaksiPenitipan::class, 'KODE_PRODUK', 'KODE_PRODUK');
    }

    public function transaksiPembelian()
    {
        return $this->belongsTo(TransaksiPembelian::class, 'ID_PEMBELIAN', 'ID_PEMBELIAN');
    }


    public function keranjang()
    {
        return $this->hasMany(Keranjang::class, 'KODE_PRODUK', 'KODE_PRODUK');
    }

    public function isInKeranjangBy($pembeliId)
    {
        return $this->keranjang->contains('ID_PEMBELI', $pembeliId);
    }

    public function foto()
    {
        return $this->hasMany(FotoProduk::class, 'KODE_PRODUK', 'KODE_PRODUK');
    }

    public function hunter()
    {
        return $this->belongsTo(Pegawai::class, 'ID_HUNTER', 'ID_PEGAWAI');
    }

    public function donasi()
    {
        return $this->hasOne(Donasi::class, 'KODE_PRODUK', 'KODE_PRODUK');
    }
}
