<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiPenitipan;
use App\Models\Produk;
use App\Models\KategoriProduk;
use App\Models\Penitip;
use App\Models\Pegawai;
use App\Models\Alamat;
use App\Models\TransaksiPembelian;
use App\Models\Donasi;
use App\Models\Organisasi;
use App\Models\RequestDonasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade as PDF;

class TransaksiPenitipanController extends Controller
{
    // View all ongoing transactions
    public function index()
    {
        // Get all ongoing transactions
        $ongoingTransactions = TransaksiPenitipan::where('STATUS_PENITIPAN', 'Berlangsung')->get();

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

    public function printNota($id)
    {
        // Ambil data transaksi penitipan berdasarkan ID
        $transaction = TransaksiPenitipan::findOrFail($id);

        // Mendapatkan informasi produk terkait
        $produk = $transaction->produk;

        // Mendapatkan informasi penitip terkait
        $penitip = $transaction->penitip;

        // Ambil alamat dari tabel Alamat berdasarkan ID_PENITIP
        $alamat = Alamat::where('ID_PENITIP', $penitip->ID_PENITIP)->first();

        // Ambil informasi pegawai terkait transaksi penitipan
        $pegawai = $transaction->pegawai;

        // Menyiapkan data untuk nota
        $nota = [
            'no_nota' => '25.' . date('m') . '.' . $transaction->ID_PENITIPAN,
            'tanggal_penitipan' => $this->formatDate($transaction->TANGGAL_PENITIPAN),
            'masa_penitipan_sampai' => $this->formatDate($transaction->TANGGAL_EXPIRED),
            'penitip' => 'T' . $penitip->ID_PENITIP . ' / ' . $penitip->NAMA_PENITIP,
            'alamat_penitip' => $alamat ? $alamat->LOKASI : 'Alamat tidak ditemukan',
            'delivery' => 'Kurir ReUseMart (' . ($pegawai ? $pegawai->NAMA_PEGAWAI : 'Tidak Ditemukan') . ')',
            'produk' => $produk->NAMA_PRODUK,
            'harga' => number_format($produk->HARGA, 0, ',', '.'),
            'garansi' => $produk->GARANSI,
            'berat_barang' => 20,
            'qc_by' => 'P' . ($pegawai ? $pegawai->ID_PEGAWAI : 'Tidak Ditemukan') . ' - ' . ($pegawai ? $pegawai->NAMA_PEGAWAI : 'Tidak Ditemukan'),
        ];

        // Generate the PDF using the view
        $pdf = \PDF::loadView('pegawai_gudang.cetak_nota', compact('nota'));

        // Return the PDF for download
        return $pdf->download('Nota_Transaksi_Penitipan_' . $transaction->ID_PENITIPAN . '.pdf');
    }


    // Helper method untuk memformat tanggal jika sudah berupa objek Carbon
    private function formatDate($date)
    {
        // Cek apakah $date sudah berupa objek Carbon, jika ya, format
        if (is_a($date, \Carbon\Carbon::class)) {
            return $date->format('d/m/Y');
        }

        // Jika bukan objek Carbon, coba parse sebagai tanggal string dan format
        try {
            return \Carbon\Carbon::parse($date)->format('d/m/Y');
        } catch (\Exception $e) {
            return 'Tanggal tidak valid';
        }
    }

    public function cetakStokGudang()
    {
        // Fetch the products with 'STATUS_PENITIPAN' as "Berlangsung"
        $produk = Produk::whereHas('transaksiPenitipan', function($query) {
            $query->where('STATUS_PENITIPAN', 'Berlangsung');
        })->get();

        // Determine the current month and pass it to the view
        $month = now()->format('F Y');

        // Return the view with the filtered data and month
        return view('owner.cetak_stok_gudang', compact('produk', 'month'));
    }

    public function cetakStokGudang_pdf()
    {
        // Fetch the products with 'STATUS_PENITIPAN' as "Berlangsung"
        $produk = Produk::whereHas('transaksiPenitipan', function($query) {
            $query->where('STATUS_PENITIPAN', 'Berlangsung');
        })->get();

        // Determine the current month and pass it to the view
        $month = now()->format('F Y');

        $pdf = \PDF::loadView('owner.cetak_stok_gudang', compact('produk', 'month'));
        return $pdf->download('cetak_stok_gudang.pdf');
    }

    public function cetakDonasiBarang(Request $request)
    {
        $tahun = $request->tahun;

        $produk = Produk::whereHas('transaksiPenitipan', function($query) {
        $query->where('STATUS_PENITIPAN', 'Didonasikan');
            })
            ->whereHas('donasi', function($query) use ($tahun) {
                $query->whereYear('TANGGAL_DONASI', $tahun);
            })
            ->get();
    //     $produk = Produk::with(['transaksiPenitipan', 'donasi.organisasi'])  // eager load relasi yg diinginkan
    // ->whereHas('transaksiPenitipan', function($query) {
    //     $query->where('STATUS_PENITIPAN', 'Didonasikan');
    // })
    // ->whereHas('donasi', function($query) use ($tahun) {
    //     $query->whereYear('TANGGAL_DONASI', $tahun);
    // })
    // ->get();
        // dd($produk);
        // Determine the current month and pass it to the view
        // $month = now()->format('F Y');

        // Return the view with the filtered data and month
        $pdf = \PDF::loadView('owner.cetak_donasi_barang', compact('produk', 'tahun'));
        return $pdf->stream('cetak_donasi_barang.pdf');
    }

    public function cetakDonasiBarangPDF(Request $request)
    {
        $tahun = $request->tahun;
        // Fetch the products with 'STATUS_PENITIPAN' as "Berlangsung"
        $produk = Produk::whereHas('transaksiPenitipan', function($query) {
        $query->where('STATUS_PENITIPAN', 'Didonasikan');
            })
            ->whereHas('donasi', function($query) use ($tahun) {
                $query->whereYear('TANGGAL_DONASI', $tahun);
            })
            ->get();


        // Determine the current month and pass it to the view
        // $month = now()->format('F Y');

        $pdf = \PDF::loadView('owner.cetak_donasi_barang', compact('produk', 'tahun'));
        return $pdf->download('cetak_donasi_barang.pdf');
    }

    public function cetakRequestDonasi()
    {
        $request = RequestDonasi::with('organisasi') // ambil data organisasi dari relasi
                    ->whereNotNull('ID_ORGANISASI')    // pastikan request punya organisasi
                    ->where('STATUS_REQUEST', 'pending')
                    ->get();

        // Determine the current month and pass it to the view
        // $month = now()->format('F Y');

        // Return the view with the filtered data and month
        $pdf = \PDF::loadView('owner.cetak_request_donasi', compact('request'));
        return $pdf->stream('cetak_request_donasi.pdf');
    }

    public function cetakRequestDonasiPDF()
    {
        $request = RequestDonasi::with('organisasi') // ambil data organisasi dari relasi
                    ->whereNotNull('ID_ORGANISASI')    // pastikan request punya organisasi
                    ->get();

        $pdf = \PDF::loadView('owner.cetak_request_donasi', compact('request'));
        return $pdf->download('cetak_request_donasi.pdf');
    }

    public function cetakTransaksiPenitip(Request $request)
    {
        $id = $request->penitip_id;

        $penitip = Penitip::find($id);
        $bulan = (int) $request->bulan; // atau apapun asal hasilnya int
        $tahun = $request->tahun;

        // $transaksi = TransaksiPenitipan::where('ID_PENITIP', $id)
        //         ->where('STATUS_PENITIPAN', 'Laku')
        //         ->whereMonth('TANGGAL_PENITIPAN', $bulan)
        //         ->whereYear('TANGGAL_PENITIPAN', $tahun)
        //         ->get();
        $transaksi = TransaksiPenitipan::where('ID_PENITIP', $id)
            ->where('STATUS_PENITIPAN', 'Laku')
            ->whereHas('produk.transaksiPembelian', function ($query) use ($bulan, $tahun) {
                $query->whereMonth('TANGGAL_LUNAS', $bulan)
                    ->whereYear('TANGGAL_LUNAS', $tahun);
            })
            ->get();
        // dd($transaksi);

        // Determine the current month and pass it to the view
        // $month = now()->format('F Y');

        // Return the view with the filtered data and month
        $pdf = \PDF::loadView('owner.cetak_transaksi_penitip', compact('penitip', 'transaksi','bulan', 'tahun'));
        return $pdf->stream('cetak_transaksi_penitip.pdf');
    }

    public function cetakTransaksiPenitipPDF(Request $request)
    {
        $id = $request->penitip_id;

        $penitip = Penitip::find($id);
        $bulan = (int) $request->bulan; // atau apapun asal hasilnya int

        $tahun = $request->tahun;

        // $transaksi = TransaksiPenitipan::where('ID_PENITIP', $id)
        //         ->where('STATUS_PENITIPAN', 'Laku')
        //         ->whereMonth('TANGGAL_PENITIPAN', $bulan)
        //         ->whereYear('TANGGAL_PENITIPAN', $tahun)
        //         ->get();
        $transaksi = TransaksiPenitipan::where('ID_PENITIP', $id)
            ->where('STATUS_PENITIPAN', 'Laku')
            ->whereHas('produk.transaksiPembelian', function ($query) use ($bulan, $tahun) {
                $query->whereMonth('TANGGAL_LUNAS', $bulan)
                    ->whereYear('TANGGAL_LUNAS', $tahun);
            })
            ->get();

        // Determine the current month and pass it to the view
        // $month = now()->format('F Y');

        // Return the view with the filtered data and month
        $pdf = \PDF::loadView('owner.cetak_transaksi_penitip', compact('penitip', 'transaksi','bulan', 'tahun'));
        return $pdf->download('cetak_transaksi_penitip.pdf');
    }

    // Mobile - Fetch the penitipan history for the given penitip
    public function indexMobile(Request $request)
    {
        // Validate that the penitip's ID is provided
        $request->validate([
            'ID_PENITIP' => 'required|exists:penitip,ID_PENITIP', // Validate penitip ID exists
        ]);

        // Fetch the penitipan history for the given penitip
        $penitipan = TransaksiPenitipan::where('ID_PENITIP', $request->ID_PENITIP)
            ->with(['produk', 'pegawai', 'penitip']) // Eager load relations
            ->orderBy('TANGGAL_PENITIPAN', 'desc') // Order by date of penitipan
            ->get();

        // Return the result as JSON
        return response()->json($penitipan);
    }

    // Method to get the details of a specific penitipan transaction - Ends with 'Mobile'
    public function showMobile($id)
    {
        // Fetch the penitipan transaction details by ID
        $penitipan = TransaksiPenitipan::with(['produk', 'pegawai', 'penitip'])
            ->where('ID_PENITIPAN', $id)
            ->first();

        // Check if the penitipan exists
        if (!$penitipan) {
            return response()->json(['message' => 'Penitipan transaction not found'], 404);
        }

        // Return the penitipan transaction details as JSON
        return response()->json($penitipan);
    }

    public function cetakLaporanKategoriPerTahun(Request $request)
    {
        $tahun = $request->tahun;
        $tanggalCetak = Carbon::now()->format('d F Y');

        $kategoriList = KategoriProduk::all();

        $laporan = [];

        $totalTerjual = 0;
        $totalGagal = 0;

        foreach ($kategoriList as $kategori) {
            // Jumlah produk terjual berdasarkan transaksi_pembelian yang ada
            $jumlahTerjual = Produk::where('ID_KATEGORI', $kategori->ID_KATEGORI)
                ->whereNotNull('ID_PEMBELIAN')
                ->whereHas('transaksiPembelian', function ($query) use ($tahun) {
                    $query->whereYear('TANGGAL_LUNAS', $tahun);
                })
                ->count();

            // Jumlah gagal terjual (expired dan bukan terjual/berlangsung) dari transaksi_penitipan
            $jumlahGagalTerjual = Produk::where('ID_KATEGORI', $kategori->ID_KATEGORI)
                ->whereHas('transaksiPenitipan', function ($query) use ($tahun) {
                    $query->whereNotIn('STATUS_PENITIPAN', ['Terjual', 'Berlangsung'])
                        ->whereDate('TANGGAL_EXPIRED', '<=', now())
                        ->whereYear('TANGGAL_EXPIRED', $tahun);
                })
                ->count();

            $laporan[] = [
                'kategori' => $kategori->NAMA_KATEGORI,
                'jumlah_terjual' => $jumlahTerjual,
                'jumlah_gagal_terjual' => $jumlahGagalTerjual,
            ];

            $totalTerjual += $jumlahTerjual;
            $totalGagal += $jumlahGagalTerjual;
        }

        $pdf = \PDF::loadView('owner.laporan_kategori_per_tahun', [
            'tahun' => $tahun,
            'tanggal_cetak' => $tanggalCetak,
            'laporan' => $laporan,
            'total_terjual' => $totalTerjual,
            'total_gagal' => $totalGagal,
        ]);

        return $pdf->stream('laporan_kategori_' . $tahun . '.pdf');
    }

    public function cetakProdukExpired()
    {
        $tanggalCetak = Carbon::now()->translatedFormat('d F Y');

        $produkExpired = Produk::with(['transaksiPenitipan.penitip'])
            ->whereHas('transaksiPenitipan', function ($query) {
                $query->where('STATUS_PENITIPAN', 'Expired');
            })
            ->get()
            ->map(function ($produk) {
                $transaksi = $produk->transaksiPenitipan;
                $penitip = optional($transaksi)->penitip;

                return [
                    'kode_produk' => strtoupper(substr($produk->NAMA_PRODUK, 0, 1)) . $produk->KODE_PRODUK,
                    'nama_produk' => $produk->NAMA_PRODUK,
                    'id_penitip' => $penitip->ID_PENITIP ?? '-',
                    'nama_penitip' => $penitip->NAMA_PENITIP ?? '-',
                    'tanggal_masuk' => optional($transaksi)->TANGGAL_MASUK,
                    'tanggal_akhir' => optional($transaksi)->TANGGAL_EXPIRED ? Carbon::parse($transaksi->TANGGAL_EXPIRED)->subDay() : null,
                    'batas_ambil' => optional($transaksi)->TANGGAL_EXPIRED ? Carbon::parse($transaksi->TANGGAL_EXPIRED)->addDays(6) : null,
                ];
            });

        $pdf = \PDF::loadView('owner.laporan_barang_penitipan_habis', [
            'produkExpired' => $produkExpired,
            'tanggal_cetak' => $tanggalCetak,
        ]);

        return $pdf->stream('laporan_produk_expired.pdf');
    }

    public function cetakLaporanKategoriPerTahunPdf(Request $request)
    {
        $tahun = $request->tahun;
        $tanggalCetak = Carbon::now()->format('d F Y');

        $kategoriList = KategoriProduk::all();

        $laporan = [];

        $totalTerjual = 0;
        $totalGagal = 0;

        foreach ($kategoriList as $kategori) {
            // Jumlah produk terjual berdasarkan transaksi_pembelian yang ada
            $jumlahTerjual = Produk::where('ID_KATEGORI', $kategori->ID_KATEGORI)
                ->whereNotNull('ID_PEMBELIAN')
                ->whereHas('transaksiPembelian', function ($query) use ($tahun) {
                    $query->whereYear('TANGGAL_LUNAS', $tahun);
                })
                ->count();

            // Jumlah gagal terjual (expired dan bukan terjual/berlangsung) dari transaksi_penitipan
            $jumlahGagalTerjual = Produk::where('ID_KATEGORI', $kategori->ID_KATEGORI)
                ->whereHas('transaksiPenitipan', function ($query) use ($tahun) {
                    $query->whereNotIn('STATUS_PENITIPAN', ['Terjual', 'Berlangsung'])
                        ->whereDate('TANGGAL_EXPIRED', '<=', now())
                        ->whereYear('TANGGAL_EXPIRED', $tahun);
                })
                ->count();

            $laporan[] = [
                'kategori' => $kategori->NAMA_KATEGORI,
                'jumlah_terjual' => $jumlahTerjual,
                'jumlah_gagal_terjual' => $jumlahGagalTerjual,
            ];

            $totalTerjual += $jumlahTerjual;
            $totalGagal += $jumlahGagalTerjual;
        }

        $pdf = \PDF::loadView('owner.laporan_kategori_per_tahun', [
            'tahun' => $tahun,
            'tanggal_cetak' => $tanggalCetak,
            'laporan' => $laporan,
            'total_terjual' => $totalTerjual,
            'total_gagal' => $totalGagal,
        ]);

        return $pdf->download('laporan_kategori_' . $tahun . '.pdf');
    }

    public function cetakProdukExpiredPdf()
    {
        $tanggalCetak = Carbon::now()->translatedFormat('d F Y');

        $produkExpired = Produk::with(['transaksiPenitipan.penitip'])
            ->whereHas('transaksiPenitipan', function ($query) {
                $query->where('STATUS_PENITIPAN', 'Expired');
            })
            ->get()
            ->map(function ($produk) {
                $transaksi = $produk->transaksiPenitipan;
                $penitip = optional($transaksi)->penitip;

                return [
                    'kode_produk' => strtoupper(substr($produk->NAMA_PRODUK, 0, 1)) . $produk->KODE_PRODUK,
                    'nama_produk' => $produk->NAMA_PRODUK,
                    'id_penitip' => $penitip->ID_PENITIP ?? '-',
                    'nama_penitip' => $penitip->NAMA_PENITIP ?? '-',
                    'tanggal_masuk' => optional($transaksi)->TANGGAL_MASUK,
                    'tanggal_akhir' => optional($transaksi)->TANGGAL_EXPIRED ? Carbon::parse($transaksi->TANGGAL_EXPIRED)->subDay() : null,
                    'batas_ambil' => optional($transaksi)->TANGGAL_EXPIRED ? Carbon::parse($transaksi->TANGGAL_EXPIRED)->addDays(6) : null,
                ];
            });

        $pdf = \PDF::loadView('owner.laporan_barang_penitipan_habis', [
            'produkExpired' => $produkExpired,
            'tanggal_cetak' => $tanggalCetak,
        ]);

        return $pdf->download('laporan_produk_expired.pdf');
    }
  
    public function showBarangTitipan($id)
    {
        // Fetch all TransaksiPenitipan for the given ID_PENITIP
        $transaksiPenitipan = TransaksiPenitipan::with(['produk', 'pegawai', 'penitip'])
            ->where('ID_PENITIP', $id)
            ->get();

        // Check if data exists
        if ($transaksiPenitipan->isEmpty()) {
            return response()->json(['message' => 'No items found for this penitip.'], 404);
        }

        // Return the data as JSON
        return response()->json($transaksiPenitipan, 200);
    }
}
