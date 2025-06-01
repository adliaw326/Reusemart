<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPembelian;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Pembeli;
use App\Models\TransaksiPenitipan;
use App\Models\Penitip;
use App\Models\Alamat;
use Illuminate\Support\Facades\DB;

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
        $alamat = Alamat::where('LOKASI', $request->ID_ALAMAT)->first();

        if (!$alamat) {
            return response()->json(['error' => 'Alamat tidak ditemukan'], 404);
        }
        $request->merge([
            'ID_ALAMAT' => $alamat->ID_ALAMAT
        ]);
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
            'TOTAL_BAYAR' => 'required|numeric',
            'POIN_DISKON' => 'required|integer',
            'ID_ALAMAT' => 'nullable|integer',
        ]);


        $transaksi = TransaksiPembelian::create($validated);
        if (!$transaksi) {
            return redirect()->back()->with('error', 'TRANSASKIK GAGAL!');
        }

        $pembeli = Pembeli::find($validated['ID_PEMBELI']);
        // if (!$pembeli) {
        //     return redirect()->back()->with('error', 'Pembeli tidak ditemukan!');
        // }
        // $pembeli->POIN_PEMBELI = $request->SISA_POIN + $request->POIN_DIDAPAT; // Set POIN_PEMBELI jika ada, default 0
        // $pembeli->save();

        // updet produk
        $produkList = $request->input('PRODUK');

        // foreach ($produkList as $produk) {
        //     $produk->ID_PEMBELIAN = $transaksi->ID_PEMBELIAN; // Set ID_PEMBELIAN pada produk
        //     $produk->ID_PEMBELI = $pembeli->ID_PEMBELI; // Set ID_PEMBELI pada produk
        //     $produk->save(); // Simpan perubahan pada produk
        // }


        // Redirect ke halaman index dengan pesan sukses
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Transaksi Pembelian berhasil ditambahkan!',
        //     'ID_PEMBELIAN' => $transaksi->ID_PEMBELIAN
        // ]);
        $ID_PEMBELI = $transaksi->ID_PEMBELI;
        $NAMA_PEMBELI = $pembeli->NAMA_PEMBELI;
        $ID_PEMBELIAN = $transaksi->ID_PEMBELIAN;
        $TOTAL_BAYAR = $transaksi->TOTAL_BAYAR;
        $SISA_POIN = $request->SISA_POIN;
        $POIN_DIDAPAT = $request->POIN_DIDAPAT;
        $PRODUK = $produkList;
        $TRANSAKSI_PEMBELIAN = $transaksi;
        $ID_ALAMAT = $request->ID_ALAMAT;
        // dd($TRANSAKSI_PEMBELIAN);
        $ID_TRANSAKSI_PEMBELIAN = $this->generateId($ID_PEMBELIAN);

        return response()->json([
            'ID_PEMBELI' => $ID_PEMBELI,
            'NAMA_PEMBELI' => $NAMA_PEMBELI,
            'ID_PEMBELIAN' => $ID_PEMBELIAN,
            'TRANSAKSI_PEMBELIAN' => $TRANSAKSI_PEMBELIAN,
            'SISA_POIN' => $SISA_POIN,
            'POIN_DIDAPAT' => $POIN_DIDAPAT,
            'PRODUK' => $PRODUK,
            'ID_ALAMAT' => $ID_ALAMAT,
            'ID_TRANSAKSI_PEMBELIAN' => $ID_TRANSAKSI_PEMBELIAN,
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
            'ID_ALAMAT' => 'nullable|integer',
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
            
            // Jika rating produk masih null, langsung masukkan rating baru
            if (is_null($currentRating)) {
                $produk->RATING = $request->input('rating');
            } else {
                // Mendapatkan rating rata-rata produk
                $currentAverageRating = $produk->RATING_RATA_RATA_P;
                
                // Menghitung rating baru dan rata-rata rating baru untuk produk
                $newRating = $request->input('rating');
                $averageRating = ($currentRating + $newRating) / 2;

                // Update rating produk dengan rata-rata baru
                $produk->RATING = $averageRating;
            }

            // Simpan perubahan rating produk
            $produk->save();

            // Temukan transaksi penitipan berdasarkan KODE_PRODUK yang menghubungkan produk dengan penitip
            $transaksiPenitipan = TransaksiPenitipan::where('KODE_PRODUK', $produk->KODE_PRODUK)->first();

            // Temukan penitip yang terkait dengan transaksi penitipan
            if ($transaksiPenitipan) {
                $penitip = Penitip::find($transaksiPenitipan->ID_PENITIP);

                if ($penitip) {
                    // Jika TOTAL_BARANG_TERJUAL adalah NULL, langsung tambahkan rating rata-rata penitip
                    if (is_null($penitip->TOTAL_BARANG_TERJUAL)) {
                        $penitip->RATING_RATA_RATA_P = $request->input('rating');
                        $penitip->TOTAL_BARANG_TERJUAL = 1;
                    } else {
                        // Mendapatkan rating rata-rata penitip dan total barang terjual penitip
                        $currentPenitipRating = $penitip->RATING_RATA_RATA_P;
                        $currentPenitipTotalBarang = $penitip->TOTAL_BARANG_TERJUAL;

                        // Menghitung rating rata-rata penitip yang baru
                        $newPenitipTotalBarang = $currentPenitipTotalBarang + 1;
                        $newPenitipRating = (($currentPenitipRating * $currentPenitipTotalBarang) + $request->input('rating')) / $newPenitipTotalBarang;

                        // Update RATING_RATA_RATA_P dan TOTAL_BARANG_TERJUAL untuk penitip
                        $penitip->RATING_RATA_RATA_P = $newPenitipRating;
                        $penitip->TOTAL_BARANG_TERJUAL = $newPenitipTotalBarang;
                    }

                    // Simpan perubahan penitip
                    $penitip->save();

                    // Update status rating transaksi menjadi 'SUDAH'
                    $transaksi->STATUS_RATING = 'SUDAH';
                    $transaksi->save();

                    // Redirect kembali ke halaman history transaksi dengan pesan sukses
                    return redirect()->route('transaksi_pembelian.history')->with('success', 'Rating berhasil diberikan dan status rating diubah menjadi SUDAH!');
                }
            }

            // Jika penitip tidak ditemukan
            return redirect()->route('transaksi_pembelian.history')->with('error', 'Penitip tidak ditemukan!');
        }

        // Jika produk tidak ditemukan
        return redirect()->route('transaksi_pembelian.history')->with('error', 'Produk tidak ditemukan!');
    }


    public function buktiBayar(Request $request, $id)
    {
        // dd($request->all());
        // Validasi file gambar bukti bayar
        $request->validate([
            'BUKTI_BAYAR' => 'required|image|max:2048', // maksimal 2MB
        ]);

        
        
        $transaksi = TransaksiPembelian::findOrFail($id);
        
        if ($request->hasFile('BUKTI_BAYAR')) {
            $file = $request->file('BUKTI_BAYAR');
            
            // Simpan file ke storage/app/public/bukti_bayar
            $path = $file->store('bukti_bayar', 'public');
            
            // Simpan path ke database
            $transaksi->BUKTI_BAYAR = $path;
            $transaksi->STATUS_TRANSAKSI = 'MENUNGGU KONFIRMASI'; // Update status transaksi
            $transaksi->TANGGAL_LUNAS = now(); // Set tanggal lunas ke waktu sekarang
            $transaksi->save();
        }

        $produkList = json_decode($request->input('PRODUK'), true);
        // dd($request);
        if (is_array($produkList)) {
            foreach ($produkList as $produk) {
                // Contoh: menyimpan ke tabel detail_pembelian
                $produkModel = Produk::find($produk['KODE_PRODUK']);
                if ($produkModel) {
                    // Contoh: update kolom TERJUAL
                    $produkModel->ID_PEMBELIAN = $transaksi->ID_PEMBELIAN; // Set ID_PEMBELIAN pada produk

                    // Contoh: update kolom STATUS jika null

                    $produkModel->save();
                }
            }
        }

        // Kembalikan response JSON yang jelas
        return response()->json([
            'message' => 'Bukti bayar berhasil diupload',
            'path' => $transaksi->BUKTI_BAYAR,
        ]);
    }

    public function generateId($idPembelian)
    {
        // Ambil tanggal sekarang atau tanggal lunas sesuai kebutuhan
        $tanggalLunas = now(); // pakai Carbon bawaan Laravel

        // Format tahun dan bulan
        $tahun = $tanggalLunas->format('Y');   // contoh: 2025
        $bulan = $tanggalLunas->format('m');   // contoh: 06

        // Gabungkan jadi nomor nota: tahun.bulan.ID_PEMBELIAN
        $nomorNota = $tahun . '.' . $bulan . '.' . $idPembelian;

        return $nomorNota;
    }

    public function findKonfirmasi()
    {
        $data = TransaksiPembelian::where('STATUS_TRANSAKSI', 'MENUNGGU KONFIRMASI')->get([
            'ID_PEMBELIAN', 'TOTAL_BAYAR', 'BUKTI_BAYAR','POIN_DISKON', 'ID_PEMBELI'
        ]);

        return response()->json($data);
    }

    public function konfirmasi($id)
    {  
        DB::beginTransaction();
        try{
            $transaksi = TransaksiPembelian::find($id);
            if (!$transaksi) {
                return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
            }

            $transaksi->STATUS_TRANSAKSI = "DISIAPKAN";
            $transaksi->TANGGAL_LUNAS = now(); // Set tanggal lunas ke waktu sekarang
            $transaksi->save();

            ////////////////NOTIFFFFF
            $idPembelian = $transaksi->ID_PEMBELIAN; // misal kolom id
            $produkIds = Produk::where('ID_PEMBELIAN', $idPembelian)->pluck('id');

            $idPenitips = TransaksiPenitipan::whereIn('KODE_PRODUK', $produkIds)
                        ->pluck('ID_PENITIP')->unique();
            
            $tokens = Penitip::whereIn('id', $idPenitips)
                      ->whereNotNull('fcm_token')
                      ->pluck('fcm_token')->toArray();

            if (!empty($tokens)) {
                sendFcmNotification(
                    $tokens,
                    'Status Transaksi Update',
                    'Barang Anda Sudah Terjual dan Sedang Disiapkan untuk Pengiriman',
                    ['transaksi_id' => $transaksi->id]
                );
            }

            $totalHarga = Produk::where('ID_PEMBELIAN', $transaksi->ID_PEMBELIAN)
                ->select(DB::raw('SUM(HARGA) as total'))
                ->value('total');
            // dd($totalHarga);

            if($transaksi->POIN_DISKON > 0){
                $pembeli = Pembeli::find($transaksi->ID_PEMBELI);
                if ($pembeli) {
                    // Update poin pembeli
                    $poinDigunakan = $transaksi->POIN_DISKON;
                    $sisaPoin = $pembeli->POIN_PEMBELI - $poinDigunakan;
                    $hargaBayar = $transaksi->TOTAL_BAYAR - ($poinDigunakan * 100);         
                    if($transaksi->STATUS_PENGIRIMAN == 'delivery' && $totalHarga < 1500000){
                        $hargaBayar = $hargaBayar - 100000;
                    }
                    $hargaBayar = max($hargaBayar, 0); // HARGA UNTUK POIN                
                    
                    $bonusPoin = $hargaBayar > 500000 
                        ? floor(($hargaBayar / 10000) * 1.2)
                        : floor($hargaBayar / 10000);
                    // dd($bonusPoin, $hargaBayar, $sisaPoin, $poinDigunakan, $pembeli->POIN_PEMBELI);
                    $pembeli->POIN_PEMBELI = $sisaPoin + $bonusPoin;
                    $pembeli->save();
                    }
            }else{
                $pembeli = Pembeli::find($transaksi->ID_PEMBELI);
                if ($pembeli) {
                    $sisaPoin = $pembeli->POIN_PEMBELI;
                    $hargaBayar = $transaksi->TOTAL_BAYAR;         
                    if($transaksi->STATUS_PENGIRIMAN == 'delivery' && $totalHarga < 1500000){
                        $hargaBayar = $hargaBayar - 100000;
                    }
                    $hargaBayar = max($hargaBayar, 0); // HARGA UNTUK POIN                
                    
                    $bonusPoin = $hargaBayar > 500000 
                        ? floor(($hargaBayar / 10000) * 1.2)
                        : floor($hargaBayar / 10000);
                    // dd($bonusPoin, $hargaBayar, $sisaPoin, $pembeli->POIN_PEMBELI);
                    $pembeli->POIN_PEMBELI = $sisaPoin + $bonusPoin;
                    $pembeli->save();
                }
            }

            DB::commit();
            return response()->json(['message' => 'Transaksi berhasil di KONFIRMASI']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan saat mengkonfirmasi transaksi: ' . $e->getMessage()], 500);
        }
    }

    public function gagalKonfirmasi($id)
    {
        $transaksi = TransaksiPembelian::find($id);
        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        $transaksi->STATUS_TRANSAKSI = "BUKTI TIDAK VALID";
        $transaksi->save();

        return response()->json(['message' => 'Transaksi telah DIBATALKAN']);
    }

}
