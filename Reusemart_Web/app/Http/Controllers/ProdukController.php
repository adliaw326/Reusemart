<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\KategoriProduk;
use App\Models\DiskusiProduk;


class ProdukController extends Controller
{
    public function index()
    {
        // Fetch all categories and products
        $kategori = KategoriProduk::all(); // Get all categories
        $produk = Produk::all(); // Get all products

        // Pass both categories and products to the view
        return view('general.home', compact('kategori', 'produk')); // Return home view from 'general' folder
    }

    public function show($kode_produk)
    {
        // Ambil produk berdasarkan KODE_PRODUK
        $produk = Produk::where('KODE_PRODUK', $kode_produk)->first();

        if (!$produk) {
            return redirect('/produk')->with('error', 'Produk tidak ditemukan.');
        }

        $diskusi = DiskusiProduk::with('children')
        ->where('KODE_PRODUK', $kode_produk)
        ->whereNull('ID_PARENT') // hanya komentar utama (root)
        ->orderBy('TANGGAL_POST', 'desc')
        ->get();

        // Ambil produk lainnya yang sedang dalam status penitipan 'sedang berlangsung'
        $produk_lainnya = Produk::whereHas('transaksiPenitipan', function ($query) {
            $query->where('STATUS_PENITIPAN', 'sedang berlangsung');
        })->where('KODE_PRODUK', '!=', $kode_produk)->limit(6)->get();

        // Ambil penitip terkait produk
        $penitip = $produk->penitip;  // Mengambil penitip yang terkait dengan produk

        return view('produk.show', compact('produk', 'produk_lainnya', 'penitip', 'diskusi'));
    }


    public function findbyId($kode_produk)
    {
        $produk = Produk::where('KODE_PRODUK', $kode_produk)->first();

        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        return response()->json($produk);
    }

    public function store(Request $request)
    {
        $request->validate([
            'KODE_PRODUK' => 'required|unique:produk,KODE_PRODUK',
            'ID_PEGAWAI' => 'required',
            'ID_KATEGORI' => 'required',
            'NAMA_PRODUK' => 'required|string|max:255',
            'KATEGORI' => 'required|string|max:255',
            'BERAT' => 'required|numeric|min:0',
            'HARGA' => 'required|numeric|min:0',
            'GARANSI' => 'nullable|date',
            'RATING' => 'nullable|numeric|between:0,5'
        ]);

        $produk = Produk::create($request->all());
        return response()->json(['message' => 'Produk berhasil ditambahkan', 'data' => $produk]);
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::find($id);
        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $request->validate([
            'ID_PEGAWAI' => 'sometimes|required',
            'ID_KATEGORI' => 'sometimes|required',
            'NAMA_PRODUK' => 'sometimes|required|string|max:255',
            'KATEGORI' => 'sometimes|required|string|max:255',
            'BERAT' => 'sometimes|required|numeric|min:0',
            'HARGA' => 'sometimes|required|numeric|min:0',
            'GARANSI' => 'nullable|date',
            'RATING' => 'nullable|numeric|between:0,5'
        ]);

        $produk->update($request->all());
        return response()->json(['message' => 'Produk berhasil diperbarui', 'data' => $produk]);
    }

    public function destroy($id)
    {
        $produk = Produk::find($id);
        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $produk->delete();
        return response()->json(['message' => 'Produk berhasil dihapus']);
    }
}
