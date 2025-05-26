<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriProduk;

class KategoriProdukController extends Controller
{
    public function index()
    {
        // Fetch all categories
        $kategori = KategoriProduk::all(); // Retrieve all categories

        // Fetch all products
        $produk = Produk::all(); // Retrieve all products

        // Pass both 'kategori' and 'produk' to the view
        return view('general.home', compact('kategori', 'produk')); // Passing both variables to 'home' view
    }


    // Menyimpan kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'ID_KATEGORI' => 'required|unique:kategori_produk,ID_KATEGORI',
            'NAMA_KATEGORI' => 'required|string|max:255',
        ]);

        $kategori = KategoriProduk::create([
            'ID_KATEGORI' => $request->ID_KATEGORI,
            'NAMA_KATEGORI' => $request->NAMA_KATEGORI,
        ]);

        return response()->json(['message' => 'Kategori berhasil ditambahkan', 'data' => $kategori]);
    }

    // Menampilkan satu kategori
    public function show($id)
    {
        $kategori = KategoriProduk::find($id);

        if (!$kategori) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        return response()->json($kategori);
    }

    // Menghapus kategori
    public function destroy($id)
    {
        $kategori = KategoriProduk::find($id);

        if (!$kategori) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        $kategori->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus']);
    }
}
