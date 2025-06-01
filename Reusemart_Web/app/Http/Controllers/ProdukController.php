<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    public function create()
    {
        // Mengambil kategori produk untuk dropdown
        $kategori = KategoriProduk::all();

        // Mendapatkan produk terakhir berdasarkan KODE_PRODUK
        $lastProduct = Produk::orderBy('KODE_PRODUK', 'desc')->first();

        // Jika ada produk, ambil angka terakhir dan tambahkan 1
        $newKodeProduk = $lastProduct ? $this->generateNewKodeProduk($lastProduct->KODE_PRODUK) : 1;

        // Menampilkan form dengan KODE_PRODUK baru
        return view('pegawai_gudang.create_produk', compact('kategori', 'newKodeProduk'));
    }

    private function generateNewKodeProduk($lastKodeProduk)
    {
        // Ambil angka dari KODE_PRODUK terakhir
        $newNumber = (int)$lastKodeProduk + 1; // Tambahkan 1 untuk KODE_PRODUK baru
        return $newNumber;
    }

    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'KODE_PRODUK' => 'required|unique:produk,KODE_PRODUK',
            'ID_KATEGORI' => 'required',
            'NAMA_PRODUK' => 'required|string|max:255',
            'BERAT' => 'required|numeric|min:0',
            'HARGA' => 'required|numeric|min:0',
            'GARANSI' => 'nullable|date',
            'FOTO_1' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',  // Validasi foto pertama
            'FOTO_2' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',  // Validasi foto kedua
        ]);

        // Mengkonversi berat dari gram ke kilogram
        $beratInKg = $request->BERAT / 1000;

        // Mengambil nama kategori berdasarkan ID_KATEGORI yang dipilih
        $kategori = KategoriProduk::find($request->ID_KATEGORI);
        $kategoriNama = $kategori ? $kategori->NAMA_KATEGORI : null;

        // Menangani upload foto pertama
        if ($request->hasFile('FOTO_1')) {
            $fileExtension1 = $request->file('FOTO_1')->getClientOriginalExtension();
            $fileName1 = $request->KODE_PRODUK . '_1.' . $fileExtension1;  // Format nama file: KODE_PRODUK_1
            $fotoPath1 = $request->file('FOTO_1')->move(public_path('foto_produk'), $fileName1);  // Menyimpan foto pertama
        } else {
            $fotoPath1 = null;  // Jika tidak ada foto pertama
        }

        // Menangani upload foto kedua
        if ($request->hasFile('FOTO_2')) {
            $fileExtension2 = $request->file('FOTO_2')->getClientOriginalExtension();
            $fileName2 = $request->KODE_PRODUK . '_2.' . $fileExtension2;  // Format nama file: KODE_PRODUK_2
            $fotoPath2 = $request->file('FOTO_2')->move(public_path('foto_produk'), $fileName2);  // Menyimpan foto kedua
        } else {
            $fotoPath2 = null;  // Jika tidak ada foto kedua
        }

        // Menyimpan produk baru dengan ID_PEGAWAI, ID_KATEGORI, dan KATEGORI (nama kategori)
        $produk = Produk::create([
            'KODE_PRODUK' => $request->KODE_PRODUK,
            'ID_PEGAWAI' => $request->ID_PEGAWAI,
            'ID_KATEGORI' => $request->ID_KATEGORI,
            'KATEGORI' => $kategoriNama,  // Menyimpan nama kategori di kolom KATEGORI
            'NAMA_PRODUK' => $request->NAMA_PRODUK,
            'BERAT' => $beratInKg,
            'HARGA' => $request->HARGA,
            'GARANSI' => $request->GARANSI,
            'FOTO' => $fotoPath1,  // Menyimpan foto path pertama
        ]);

        return response()->json(['message' => 'Produk berhasil ditambahkan', 'data' => $produk]);
    }

    // public function update(Request $request, $id)
    // {
    //     $produk = Produk::find($id);
    //     if (!$produk) {
    //         return response()->json(['message' => 'Produk tidak ditemukan'], 404);
    //     }

    //     $request->validate([
    //         'ID_PEGAWAI' => 'sometimes|required',
    //         'ID_KATEGORI' => 'sometimes|required',
    //         'NAMA_PRODUK' => 'sometimes|required|string|max:255',
    //         'KATEGORI' => 'sometimes|required|string|max:255',
    //         'BERAT' => 'sometimes|required|numeric|min:0',
    //         'HARGA' => 'sometimes|required|numeric|min:0',
    //         'GARANSI' => 'nullable|date',
    //         'RATING' => 'nullable|numeric|between:0,5'
    //     ]);

    //     $produk->update($request->all());
    //     return response()->json(['message' => 'Produk berhasil diperbarui', 'data' => $produk]);
    // }

    public function destroy($id)
    {
        $produk = Produk::find($id);
        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $produk->delete();
        return response()->json(['message' => 'Produk berhasil dihapus']);
    }

    public function tampil()
    {
        // Mengambil semua produk
        $produk = Produk::all();

        // Mengirim data produk ke tampilan
        return view('pegawai_gudang.show_produk', compact('produk'));
    }

    public function delete($kode_produk)
    {
        // Mencari produk berdasarkan KODE_PRODUK
        $produk = Produk::where('KODE_PRODUK', $kode_produk)->first();

        if (!$produk) {
            // Jika produk tidak ditemukan, kembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->route('pegawai_gudang.show_produk')->with('error', 'Produk tidak ditemukan!');
        }

        // Cek jika foto produk ada, dan hapus foto yang terkait dengan produk ini
        $foto1 = $produk->KODE_PRODUK . '_1.jpg';
        $foto2 = $produk->KODE_PRODUK . '_2.jpg';

        // Menghapus foto produk 1 jika ada
        $foto1Path = public_path('foto_produk/' . $foto1);
        if (file_exists($foto1Path)) {
            unlink($foto1Path); // Menghapus foto 1
        }

        // Menghapus foto produk 2 jika ada
        $foto2Path = public_path('foto_produk/' . $foto2);
        if (file_exists($foto2Path)) {
            unlink($foto2Path); // Menghapus foto 2
        }

        // Menghapus produk dari database
        $produk->delete();

        // Mengarahkan kembali ke halaman daftar produk dengan pesan sukses
        return redirect()->route('pegawai_gudang.show_produk')->with('success', 'Produk beserta foto terkait berhasil dihapus!');
    }


    // Show the edit form (GET method)
    public function edit($kode_produk)
    {
        $produk = Produk::findOrFail($kode_produk); // Find the product by KODE_PRODUK
        $kategori = KategoriProduk::all(); // Get all categories for the select field

        return view('pegawai_gudang.update_produk', compact('produk', 'kategori'));
    }


public function update(Request $request, $kode_produk)
{
    // Validate the incoming data
    $request->validate([
        'NAMA_PRODUK' => 'required|string|max:255',
        'BERAT' => 'required|numeric|min:0',
        'HARGA' => 'required|numeric|min:0',
        'GARANSI' => 'nullable|date',
        'FOTO_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'FOTO_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'ID_KATEGORI' => 'required|exists:kategori_produk,ID_KATEGORI', // Validate that the category exists
    ]);

    // Find the product by KODE_PRODUK
    $produk = Produk::findOrFail($kode_produk);

    // Retrieve the category based on ID_KATEGORI
    $kategori = KategoriProduk::find($request->ID_KATEGORI);
    $kategoriNama = $kategori ? $kategori->NAMA_KATEGORI : null;

    // Update product details (including category)
    $produk->NAMA_PRODUK = $request->NAMA_PRODUK;
    $produk->BERAT = $request->BERAT;
    $produk->HARGA = $request->HARGA;
    $produk->GARANSI = $request->GARANSI;
    $produk->ID_KATEGORI = $request->ID_KATEGORI; // Update the category ID

    // Handle FOTO_1 update if a new image is uploaded
    if ($request->hasFile('FOTO_1')) {
        // Delete the old FOTO_1 if exists
        $oldFoto1Path = public_path('foto_produk/' . $produk->KODE_PRODUK . '_1.jpg');
        if (file_exists($oldFoto1Path)) {
            unlink($oldFoto1Path); // Delete old photo
        }

        // Save the new FOTO_1
        $fileExtension1 = $request->file('FOTO_1')->getClientOriginalExtension();
        $fileName1 = $produk->KODE_PRODUK . '_1.' . $fileExtension1;
        $request->file('FOTO_1')->move(public_path('foto_produk'), $fileName1); // Save the file in public/foto_produk/
    }

    // Handle FOTO_2 update if a new image is uploaded
    if ($request->hasFile('FOTO_2')) {
        // Delete the old FOTO_2 if exists
        $oldFoto2Path = public_path('foto_produk/' . $produk->KODE_PRODUK . '_2.jpg');
        if (file_exists($oldFoto2Path)) {
            unlink($oldFoto2Path); // Delete old photo
        }

        // Save the new FOTO_2
        $fileExtension2 = $request->file('FOTO_2')->getClientOriginalExtension();
        $fileName2 = $produk->KODE_PRODUK . '_2.' . $fileExtension2;
        $request->file('FOTO_2')->move(public_path('foto_produk'), $fileName2); // Save the file in public/foto_produk/
    }

    // Save the updated product
    $produk->save();

    // Redirect to the product listing page with a success message
    return redirect()->route('pegawai_gudang.show_produk')->with('success', 'Produk berhasil diperbarui!');
}
}
