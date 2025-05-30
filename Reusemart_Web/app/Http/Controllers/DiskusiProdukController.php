<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiskusiProduk;
use Carbon\Carbon;
define('Diskusi', 'DiskusiProduk');

class DiskusiProdukController extends Controller
{
    public function getDiskusiByProduk($kodeProduk)
    {
        // Ambil diskusi utama (id_parent = null) untuk produk tersebut
        $diskusi = DiskusiProduk::where('KODE_PRODUK', $kodeProduk)
                        ->with('children')  // relasi children untuk reply, definisikan di model Diskusi
                        ->whereNull('ID_PARENT')
                        ->orderBy('TANGGAL_POST', 'desc')
                        ->get();

        return response()->json($diskusi);
    }

    // Menyimpan diskusi baru
    public function store(Request $request)
    {
        $request->validate([
            'KODE_PRODUK' => 'required|exists:produk,KODE_PRODUK',
            'ISI_DISKUSI' => 'required|string',
            'ID_PARENT' => 'nullable|exists:diskusi_produk,ID_DISKUSI',
        ]);

        DiskusiProduk::create([
            'KODE_PRODUK' => $request->KODE_PRODUK,
            'ISI_DISKUSI' => $request->ISI_DISKUSI,
            'ID_PEGAWAI' => auth()->user()->role == 'pegawai' ? auth()->id() : null,
            'ID_PEMBELI' => auth()->user()->role == 'pembeli' ? auth()->id() : null,
            'ID_PARENT' => $request->ID_PARENT,
            'TANGGAL_POST' => now(),
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil dikirim!');
    }


    public function index($kodeProduk)
    {
        // Ambil semua diskusi produk berdasarkan KODE_PRODUK, urut tanggal asc
        $diskusi = DiskusiProduk::where('KODE_PRODUK', $kodeProduk)
            ->orderBy('TANGGAL_POST', 'asc')
            ->get();

        return response()->json($diskusi);
    }

    // Menampilkan diskusi berdasarkan produk
    public function showByProduk($kode_produk)
    {
        $diskusi = DiskusiProduk::where('KODE_PRODUK', $kode_produk)->get();
        return response()->json($diskusi);
    }
}
