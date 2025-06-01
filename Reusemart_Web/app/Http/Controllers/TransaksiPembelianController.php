<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPembelian;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Pembeli;

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
        // dd($request->all());
        $validated = $request->validate([
            'ID_PEMBELI' => 'required|string',
            'STATUS_TRANSAKSI' => 'required|string',
            'TANGGAL_PESAN' => 'required|date',
            'TANGGAL_LUNAS' => 'nullable|date',
            'TANGGAL_KIRIM' => 'nullable|date',
            'TANGGAL_SAMPAI' => 'nullable|date',
            'STATUS_RATING' => 'nullable|string',
            'STATUS_PENGIRIMAN' => 'nullable|string',
            'BUKTI_BAYAR' => 'nullable', // Validasi file bukti bayar
            'TOTAL_BAYAR' => 'required|numeric'
        ]);

        $transaksi = TransaksiPembelian::create($validated);

        $pembeli = Pembeli::find($validated['ID_PEMBELI']);
        if (!$pembeli) {
            return redirect()->back()->with('error', 'Pembeli tidak ditemukan!');
        }
        $pembeli->POIN_PEMBELI = $request->SISA_POIN + $request->POIN_DIDAPAT; // Set POIN_PEMBELI jika ada, default 0
        $pembeli->save();

        //updet produk
        // $produkList = $request->input('PRODUK');

        // // foreach ($produkList as $produk) {
        // //     $produk->ID_PEMBELIAN = $transaksi->ID_PEMBELIAN; // Set ID_PEMBELIAN pada produk
        // //     $produk->ID_PEMBELI = $pembeli->ID_PEMBELI; // Set ID_PEMBELI pada produk
        // //     $produk->save(); // Simpan perubahan pada produk
        // // }


        // Redirect ke halaman index dengan pesan sukses
        return response()->json([
            'success' => true,
            'message' => 'Transaksi Pembelian berhasil ditambahkan!',
            'ID_PEMBELIAN' => $transaksi->ID_PEMBELIAN
        ]);
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

        // Temukan produk terkait berdasarkan ID_PEMBELIAN
        $produk = Produk::where('ID_PEMBELIAN', $transaksi->ID_PEMBELIAN)->first(); // Menggunakan ID_PEMBELIAN untuk mencari produk

        // Jika produk ditemukan
        if ($produk) {
            // Mendapatkan rating produk yang sudah ada
            $currentRating = $produk->RATING;

            // Menghitung rata-rata rating baru
            $newRating = $request->input('rating');
            $averageRating = ($currentRating + $newRating) / 2;

            // Update rating produk dengan rata-rata baru
            $produk->RATING = $averageRating;
            $produk->save();

            // Update status rating transaksi menjadi 'SUDAH'
            $transaksi->STATUS_RATING = 'SUDAH';
            $transaksi->save();

            // Redirect kembali ke halaman history transaksi dengan pesan sukses
            return redirect()->route('transaksi_pembelian.history')->with('success', 'Rating berhasil diberikan dan status rating diubah menjadi SUDAH!');
        }

        // Jika produk tidak ditemukan
        return redirect()->route('transaksi_pembelian.history')->with('error', 'Produk tidak ditemukan!');
    }

    public function buktiBayar($id)
    {
        $transaksi = TransaksiPembelian::findOrFail($id);

        // Jika ingin juga ambil data relasi (misal produk), bisa gunakan with()
        // $transaksi = TransaksiPembelian::with('produk')->findOrFail($id);

        return view('produk.bukti_bayar', compact('transaksi'));
    }



}
