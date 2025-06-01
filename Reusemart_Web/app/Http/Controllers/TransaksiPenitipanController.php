<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiPenitipan;
use App\Models\Produk;
use App\Models\KategoriProduk;
use App\Models\Penitip;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TransaksiPenitipanController extends Controller
{
    public function index()
    {
        // Get all ongoing transactions
        $ongoingTransactions = TransaksiPenitipan::where('STATUS_PENITIPAN', 'sedang berlangsung')->get();

        // Get the product codes associated with ongoing transactions
        $ongoingProductCodes = $ongoingTransactions->pluck('KODE_PRODUK');

        // Retrieve the products related to the ongoing transactions, including penitip relationship
        $produk = Produk::whereIn('KODE_PRODUK', $ongoingProductCodes)->with('transaksiPenitipan.penitip')->get();

        // Retrieve categories or any other data you need
        $kategori = KategoriProduk::all();

        return view('general.home', compact('produk', 'kategori'));
    }

    public function index2()
    {
        // Get all transactions (without filtering by STATUS_PENITIPAN)
        $ongoingTransactions = TransaksiPenitipan::get(); // Retrieve all transactions

        // Retrieve the product codes associated with the transactions
        $ongoingProductCodes = $ongoingTransactions->pluck('KODE_PRODUK');

        // Retrieve the products related to the ongoing transactions, including penitip relationship
        $produk = Produk::whereIn('KODE_PRODUK', $ongoingProductCodes)->with('transaksiPenitipan.penitip')->get();

        // Retrieve categories or any other data you need
        $kategori = KategoriProduk::all();

        // Pass the data to the view
        return view('pegawai_gudang.show_transaksi_penitipan', compact('ongoingTransactions', 'produk', 'kategori'));
    }

    public function update_transaksi_penitipan($id)
    {
        // Retrieve the specific penitipan (transaction) by ID
        $penitipan = TransaksiPenitipan::findOrFail($id);

        // Retrieve categories if needed
        $kategori = KategoriProduk::all();

        // Pass the penitipan and kategori data to the update_transaksi_penitipan view
        return view('pegawai_gudang.update_transaksi_penitipan', compact('penitipan', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $request->validate([
            'KODE_PRODUK' => 'required|string|max:255',
            'ID_PEMBELI' => 'required|exists:pembeli,ID_PEMBELI',
            'TANGGAL_PENITIPAN' => 'required|date',
            'STATUS_PENITIPAN' => 'required|string',
            'ID_KATEGORI' => 'required|exists:kategori_produk,ID_KATEGORI',
        ]);

        // Find the transaction by ID
        $penitipan = TransaksiPenitipan::findOrFail($id);

        // Update the transaction data
        $penitipan->KODE_PRODUK = $request->KODE_PRODUK;
        $penitipan->ID_PEMBELI = $request->ID_PEMBELI;
        $penitipan->TANGGAL_PENITIPAN = $request->TANGGAL_PENITIPAN;
        $penitipan->STATUS_PENITIPAN = $request->STATUS_PENITIPAN;
        $penitipan->ID_KATEGORI = $request->ID_KATEGORI;

        // Save the updated transaction
        $penitipan->save();

        // Redirect back with success message
        return redirect()->route('pegawai_gudang.show_transaksi_penitipan')->with('success', 'Transaksi Penitipan berhasil diperbarui!');
    }

    public function delete($id)
    {
        // Find the penitipan (transaction) by ID
        $penitipan = TransaksiPenitipan::findOrFail($id);

        // Optionally, delete related photos or files (if necessary)
        // Example for deleting related product photos:
        $foto1 = public_path('foto_produk/' . $penitipan->KODE_PRODUK . '_1.jpg');
        $foto2 = public_path('foto_produk/' . $penitipan->KODE_PRODUK . '_2.jpg');

        // Check if the first photo exists and delete it
        if (file_exists($foto1)) {
            unlink($foto1); // Delete photo 1
        }

        // Check if the second photo exists and delete it
        if (file_exists($foto2)) {
            unlink($foto2); // Delete photo 2
        }

        // Delete the penitipan (transaction) from the database
        $penitipan->delete();

        // Redirect back with a success message
        return redirect()->route('pegawai_gudang.show_transaksi_penitipan')->with('success', 'Transaksi Penitipan berhasil dihapus!');
    }

    public function create()
    {
        // Get the list of available products and penitip (optional)
        $produk = Produk::all(); // Get all products
        $penitip = Penitip::all(); // Get all penitip (depositors)

        // Return the view with data
        return view('pegawai_gudang.create_transaksi_penitipan', compact('produk', 'penitip'));
    }

    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'KODE_PRODUK' => 'required|exists:produk,KODE_PRODUK',
            'ID_PENITIP' => 'required|exists:penitip,ID_PENITIP',
            'TANGGAL_PENITIPAN' => 'required|date',
            'STATUS_PENITIPAN' => 'required|string',
            'TANGGAL_EXPIRED' => 'required|date',
        ]);

        // Create new Transaksi Penitipan
        $penitipan = new TransaksiPenitipan([
            'KODE_PRODUK' => $request->KODE_PRODUK,
            'ID_PENITIP' => $request->ID_PENITIP,
            'TANGGAL_PENITIPAN' => $request->TANGGAL_PENITIPAN,
            'STATUS_PENITIPAN' => $request->STATUS_PENITIPAN,
            'TANGGAL_EXPIRED' => $request->TANGGAL_EXPIRED,
        ]);

        // Save to the database
        $penitipan->save();

        // Redirect to show all transactions or any page you want
        return redirect()->route('pegawai_gudang.show_transaksi_penitipan')->with('success', 'Transaksi Penitipan berhasil ditambahkan!');
    }

    public function getTransaksiBerlangsung(Request $request)
    {
        $userId = $request->input('userId');

        if (!$userId) {
            return response()->json(['error' => 'User ID dibutuhkan'], 400);
        }

        $transaksi = TransaksiPenitipan::with('produk')
            ->where('ID_PENITIP', $userId)
            ->whereIn('STATUS_PENITIPAN', ['Berlangsung', 'Akan Diambil'])
            ->orderBy('TANGGAL_PENITIPAN', 'asc')
            ->get();

        return response()->json($transaksi);
    }

    // TransaksiController.php
    public function perpanjangWaktu(Request $request)
    {
        $id = $request->input('id_penitipan');
        $transaksi = TransaksiPenitipan::find($id);

        if (!$transaksi) {
            return response()->json(['error' => 'Transaksi tidak ditemukan.'], 404);
        }

        if ($transaksi->STATUS_PENITIPAN !== 'Berlangsung') {
            return response()->json(['error' => 'Hanya transaksi dengan status "Berlangsung" yang bisa diperpanjang'], 400);
        }

        if ($transaksi->STATUS_PERPANJANGAN !== null) {
            return response()->json(['error' => 'Transaksi sudah diperpanjang.'], 400);
        }

        $transaksi->TANGGAL_EXPIRED = \Carbon\Carbon::parse($transaksi->TANGGAL_EXPIRED)->addDays(30);
        $transaksi->STATUS_PERPANJANGAN = 'Sudah';
        $transaksi->save();

        return response()->json(['message' => 'Berhasil diperpanjang.']);
    }

    public function ambilPenitipan(Request $request)
    {
        $id = $request->input('id_penitipan');

        $penitipan = TransaksiPenitipan::find($id);
        if (!$penitipan) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        if ($penitipan->STATUS_PENITIPAN !== 'Berlangsung') {
            return response()->json(['error' => 'Hanya transaksi dengan status "Berlangsung" yang bisa diambil'], 400);
        }

        $penitipan->STATUS_PENITIPAN = 'Akan Diambil';
        $penitipan->save();

        return response()->json(['message' => 'Status berhasil diubah menjadi Akan Diambil']);
    }

    public function markAsTaken($id)
    {
        $transaksi = TransaksiPenitipan::findOrFail($id);

        if ($transaksi->STATUS_PENITIPAN !== 'Akan Diambil') {
            return redirect()->back()->with('error', 'Status bukan "Akan Diambil".');
        }

        $transaksi->STATUS_PENITIPAN = 'Sudah Diambil';
        $transaksi->TANGGAL_DIAMBIL = Carbon::now(); // gunakan Carbon untuk sysdate
        $transaksi->save();

        return redirect()->back()->with('success', 'Transaksi berhasil ditandai sebagai sudah diambil.');
    }
}
