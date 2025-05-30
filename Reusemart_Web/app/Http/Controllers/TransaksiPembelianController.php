<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPembelian;
use Illuminate\Http\Request;
use App\Models\Produk;

class TransaksiPembelianController extends Controller
{
    public function index()
    {
        // Mengambil transaksi pembelian dengan relasi produk dan pembeli
        $transaksiPembelian = TransaksiPembelian::with('produk', 'pembeli')->get();

        return view('transaksi_pembelian.history', compact('transaksiPembelian'));
    }


    // Menampilkan form untuk menambah transaksi pembelian
    public function create()
    {
        return view('transaksi_pembelian.create');
    }

    // Menyimpan transaksi pembelian baru
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'ID_PEMBELIAN' => 'required|string|unique:transaksi_pembelian,ID_PEMBELIAN',
            'ID_PEMBELI' => 'required|string',
            'STATUS_TRANSAKSI' => 'required|string',
            'TANGGAL_PESAN' => 'required|date',
            'TANGGAL_LUNAS' => 'nullable|date',
            'TANGGAL_KIRIM' => 'nullable|date',
            'TANGGAL_SAMPAI' => 'nullable|date',
            'STATUS_RATING' => 'nullable|string',
        ]);

        // Membuat transaksi pembelian baru
        TransaksiPembelian::create($validated);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('transaksi_pembelian.index')->with('success', 'Transaksi Pembelian berhasil ditambahkan!');
    }

    // Menampilkan riwayat transaksi pembelian dengan status rating 'BELUM'
    public function history()
    {
        // Ambil transaksi dengan STATUS_RATING 'BELUM'
        $transaksiPembelian = TransaksiPembelian::where('STATUS_RATING', 'BELUM')->get();
        
        return view('transaksi_pembelian.history', compact('transaksiPembelian'));
    }

    // Menampilkan detail transaksi pembelian
    public function show($id)
    {
        $transaksi = TransaksiPembelian::findOrFail($id);
        return view('transaksi_pembelian.show', compact('transaksi'));
    }

    // Menampilkan form untuk mengedit transaksi pembelian
    public function edit($id)
    {
        $transaksi = TransaksiPembelian::findOrFail($id);
        return view('transaksi_pembelian.edit', compact('transaksi'));
    }

    // Memperbarui transaksi pembelian
    public function update(Request $request, $id)
    {
        $transaksi = TransaksiPembelian::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'ID_PEMBELIAN' => 'required|string|unique:transaksi_pembelian,ID_PEMBELIAN,' . $transaksi->ID_PEMBELIAN,
            'ID_PEMBELI' => 'required|string',
            'STATUS_TRANSAKSI' => 'required|string',
            'TANGGAL_PESAN' => 'required|date',
            'TANGGAL_LUNAS' => 'nullable|date',
            'TANGGAL_KIRIM' => 'nullable|date',
            'TANGGAL_SAMPAI' => 'nullable|date',
            'STATUS_RATING' => 'nullable|string',
        ]);

        // Update data transaksi pembelian
        $transaksi->update($validated);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('transaksi_pembelian.index')->with('success', 'Transaksi Pembelian berhasil diperbarui!');
    }

    // Menghapus transaksi pembelian
    public function destroy($id)
    {
        $transaksi = TransaksiPembelian::findOrFail($id);
        $transaksi->delete();

        return redirect()->route('transaksi_pembelian.index')->with('success', 'Transaksi Pembelian berhasil dihapus!');
    }

    public function rating(Request $request, $id)
    {
        // Validasi rating yang diterima
        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

        // Temukan transaksi pembelian berdasarkan ID
        $transaksi = TransaksiPembelian::findOrFail($id);

        // Update status rating menjadi 'SUDAH'
        $transaksi->STATUS_RATING = 'SUDAH'; // Mengganti nama kolom menjadi STATUS_RATING
        $transaksi->save();

        // Temukan produk terkait berdasarkan ID_PEMBELIAN
        $produk = Produk::find($transaksi->ID_PEMBELIAN); // Gunakan ID_PEMBELIAN untuk mencari produk

        // Jika produk ditemukan, hitung rata-rata rating produk
        if ($produk) {
            // Mengambil semua rating yang sudah diberikan pada produk tersebut
            $ratings = TransaksiPembelian::where('ID_PEMBELIAN', $transaksi->ID_PEMBELIAN)
                                        ->where('STATUS_RATING', 'SUDAH') // Hanya ambil transaksi yang sudah diberi rating
                                        ->pluck('rating'); // Ambil semua rating yang sudah diberikan

            // Jika ada rating, hitung rata-rata rating
            if ($ratings->count() > 0) {
                $averageRating = $ratings->avg(); // Menghitung rata-rata rating
            } else {
                $averageRating = 0; // Jika tidak ada rating, set 0
            }

            // Update rating produk dengan rata-rata baru
            $produk->RATING = $averageRating; // Update rating produk
            $produk->save(); // Simpan perubahan rating produk
        }

        // Redirect kembali ke halaman history transaksi dengan pesan sukses
        return redirect()->route('transaksi_pembelian.history')->with('success', 'Rating berhasil diberikan dan status rating diubah menjadi SUDAH!');
    }
}
