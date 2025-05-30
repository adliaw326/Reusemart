<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keranjang;

class KeranjangController extends Controller
{
    // Tampilkan semua data keranjang (bisa di-restrict nanti ke pembeli login)
    public function index()
    {
        $keranjang = Keranjang::with(['pembeli', 'produk'])->get();
        return response()->json($keranjang);
    }

    // Tambah produk ke keranjang (tidak pakai Auth)
    public function store($userId, $kodeProduk)
    {
        $exists = Keranjang::where('ID_PEMBELI', $userId)
                    ->where('KODE_PRODUK', $kodeProduk)
                    ->exists();

        if ($exists) {
            return response()->json(['message' => 'Produk sudah ada di keranjang'], 409);
        }

        $keranjang = Keranjang::create([
            'ID_PEMBELI' => $userId,
            'KODE_PRODUK' => $kodeProduk,
            'TANGGAL_TAMBAH' => now()
        ]);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'data' => $keranjang
        ], 201);
    }

    // Hapus produk dari keranjang (tanpa auth)
    public function destroy($userId, $kodeProduk)
    {
        $keranjang = Keranjang::where('ID_PEMBELI', $userId)
                            ->where('KODE_PRODUK', $kodeProduk)
                            ->first();

        if (!$keranjang) {
            return response()->json(['message' => 'Data keranjang tidak ditemukan'], 404);
        }

        $keranjang->delete();

        return response()->json(['message' => 'Produk berhasil dihapus dari keranjang']);
    }

    // Cek apakah produk ada di keranjang user tertentu
    public function checkInKeranjang($userId, $kodeProduk)
    {
        $exists = Keranjang::where('ID_PEMBELI', $userId)
                        ->where('KODE_PRODUK', $kodeProduk)
                        ->exists();

        return response()->json(['exists' => $exists]);
    }

}
