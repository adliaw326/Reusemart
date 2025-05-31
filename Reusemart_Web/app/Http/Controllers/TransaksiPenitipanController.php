<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiPenitipan;
use App\Models\Produk;
use App\Models\KategoriProduk;
use App\Models\Penitip;

class TransaksiPenitipanController extends Controller
{
    // View all ongoing transactions
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

    // View all transactions (without filtering by STATUS_PENITIPAN)
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

    // Display the edit form for the transaction
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
    $validatedData = $request->validate([
        'ID_PEGAWAI' => 'required|exists:pegawai,ID_PEGAWAI', // Ensure ID_PEGAWAI exists in the pegawai table
        'KODE_PRODUK' => 'required|exists:produk,KODE_PRODUK', // Ensure KODE_PRODUK exists in the produk table
        'ID_PENITIP' => 'required|exists:penitip,ID_PENITIP', // Ensure ID_PENITIP exists in the penitip table
        'TANGGAL_PENITIPAN' => 'required|date', // Validate the date of deposit
        'STATUS_PENITIPAN' => 'required|string', // Validate the status of deposit
        'TANGGAL_EXPIRED' => 'required|date', // Validate the expiration date
        'FOTO_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate FOTO_1 upload
        'FOTO_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate FOTO_2 upload
    ]);

    // Find the transaksi penitipan by ID
    $penitipan = TransaksiPenitipan::findOrFail($id);

    // Get the associated product from the produk table
    $produk = Produk::findOrFail($validatedData['KODE_PRODUK']); // Get product using KODE_PRODUK

    // Update the transaksi penitipan data
    $penitipan->ID_PEGAWAI = $validatedData['ID_PEGAWAI']; // Update ID_PEGAWAI
    $penitipan->KODE_PRODUK = $validatedData['KODE_PRODUK']; // Update KODE_PRODUK
    $penitipan->ID_PENITIP = $validatedData['ID_PENITIP']; // Update ID_PENITIP
    $penitipan->TANGGAL_PENITIPAN = $validatedData['TANGGAL_PENITIPAN']; // Update TANGGAL_PENITIPAN
    $penitipan->STATUS_PENITIPAN = $validatedData['STATUS_PENITIPAN']; // Update STATUS_PENITIPAN
    $penitipan->TANGGAL_EXPIRED = $validatedData['TANGGAL_EXPIRED']; // Update TANGGAL_EXPIRED

    // Handle FOTO_1 update if a new image is uploaded
    if ($request->hasFile('FOTO_1')) {
        // Delete the old FOTO_1 if exists in foto_produk
        $oldFoto1Path = public_path('foto_produk/' . $produk->KODE_PRODUK . '_1.jpg');
        if (file_exists($oldFoto1Path)) {
            unlink($oldFoto1Path); // Delete old photo
        }

        // Save the new FOTO_1 in foto_produk
        $fileExtension1 = $request->file('FOTO_1')->getClientOriginalExtension();
        $fileName1 = $produk->KODE_PRODUK . '_1.' . $fileExtension1;
        $request->file('FOTO_1')->move(public_path('foto_produk'), $fileName1); // Save the file in public/foto_produk/
    }

    // Handle FOTO_2 update if a new image is uploaded
    if ($request->hasFile('FOTO_2')) {
        // Delete the old FOTO_2 if exists in foto_produk
        $oldFoto2Path = public_path('foto_produk/' . $produk->KODE_PRODUK . '_2.jpg');
        if (file_exists($oldFoto2Path)) {
            unlink($oldFoto2Path); // Delete old photo
        }

        // Save the new FOTO_2 in foto_produk
        $fileExtension2 = $request->file('FOTO_2')->getClientOriginalExtension();
        $fileName2 = $produk->KODE_PRODUK . '_2.' . $fileExtension2;
        $request->file('FOTO_2')->move(public_path('foto_produk'), $fileName2); // Save the file in public/foto_produk/
    }

    // Save the updated transaksi penitipan record
    $penitipan->save();

    // Redirect back with success message
    return redirect()->route('pegawai_gudang.show_transaksi_penitipan')->with('success', 'Transaksi Penitipan berhasil diperbarui!');
}


    // Delete a transaction from the database
    public function delete($id)
    {
        // Find the penitipan (transaction) by ID
        $penitipan = TransaksiPenitipan::findOrFail($id);

        // Delete the penitipan (transaction) from the database
        $penitipan->delete();

        // Redirect back with a success message
        return redirect()->route('pegawai_gudang.show_transaksi_penitipan')->with('success', 'Transaksi Penitipan berhasil dihapus!');
    }

    public function create()
    {
        $produk = Produk::all(); // Get all products
        $penitip = Penitip::all(); // Get all penitip (depositors)

        // Get the last ID_PENITIPAN from the database
        $lastPenitipan = TransaksiPenitipan::latest('ID_PENITIPAN')->first();

        // Generate new ID_PENITIPAN by incrementing the last one
        $newIDPenitipan = $lastPenitipan ? $lastPenitipan->ID_PENITIPAN + 1 : 1; // If no records, start at 1

        return view('pegawai_gudang.create_transaksi_penitipan', compact('produk', 'penitip', 'newIDPenitipan'));
    }

    public function store(Request $request)
    {
        // Validate the form inputs
        $validatedData = $request->validate([
            'ID_PEGAWAI' => 'required|exists:pegawai,ID_PEGAWAI', // Ensure ID_PEGAWAI exists in the pegawai table
            'KODE_PRODUK' => 'required|exists:produk,KODE_PRODUK', // Ensure KODE_PRODUK exists in the produk table
            'ID_PENITIP' => 'required|exists:penitip,ID_PENITIP', // Ensure ID_PENITIP exists in the penitip table
            'TANGGAL_PENITIPAN' => 'required|date', // Validate the date of deposit
            'STATUS_PENITIPAN' => 'required|string', // Validate the status of deposit
            'TANGGAL_EXPIRED' => 'required|date', // Validate the expiration date
        ]);

        // Get the last ID_PENITIPAN from the database
        $lastPenitipan = TransaksiPenitipan::latest('ID_PENITIPAN')->first();

        // Generate new ID_PENITIPAN by incrementing the last one
        $newIDPenitipan = $lastPenitipan ? $lastPenitipan->ID_PENITIPAN + 1 : 1; // If no records, start at 1

        // Create a new Transaksi Penitipan record
        $transaksiPenitipan = new TransaksiPenitipan();
        $transaksiPenitipan->ID_PENITIPAN = $newIDPenitipan; // Assign new ID_PENITIPAN
        $transaksiPenitipan->ID_PEGAWAI = $validatedData['ID_PEGAWAI']; // Store ID_PEGAWAI
        $transaksiPenitipan->KODE_PRODUK = $validatedData['KODE_PRODUK']; // Store KODE_PRODUK
        $transaksiPenitipan->ID_PENITIP = $validatedData['ID_PENITIP']; // Store ID_PENITIP
        $transaksiPenitipan->TANGGAL_PENITIPAN = $validatedData['TANGGAL_PENITIPAN']; // Store TANGGAL_PENITIPAN
        $transaksiPenitipan->STATUS_PENITIPAN = $validatedData['STATUS_PENITIPAN']; // Store STATUS_PENITIPAN
        $transaksiPenitipan->TANGGAL_EXPIRED = $validatedData['TANGGAL_EXPIRED']; // Store TANGGAL_EXPIRED

        // Save the record to the database
        $transaksiPenitipan->save();

        // Redirect to the 'show_transaksi_penitipan' route with success message
        return redirect()->route('pegawai_gudang.show_transaksi_penitipan')
                        ->with('success', 'Transaksi Penitipan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // Fetch the Transaksi Penitipan by ID
        $transaksiPenitipan = TransaksiPenitipan::findOrFail($id);

        // Fetch Produk and Penitip data for the dropdowns
        $produk = Produk::all();
        $penitip = Penitip::all();

        // Pass the data to the view
        return view('pegawai_gudang.update_transaksi_penitipan', compact('transaksiPenitipan', 'produk', 'penitip'));
    }
}