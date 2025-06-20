<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPembelian;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Pembeli;
use App\Models\TransaksiPenitipan;
use App\Models\Penitip;
use App\Models\Keranjang;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Alamat;
use App\Models\Notifikasi;
// use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Komisi;

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
        // $request->TANGGAL_PESAN = Carbon::now()->format('Y-m-d H:i:s');
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

        $validated['TANGGAL_PESAN'] = Carbon::now()->format('Y-m-d H:i:s');

        if (!empty($validated['TANGGAL_LUNAS'])) {
            $validated['TANGGAL_LUNAS'] = Carbon::parse($validated['TANGGAL_LUNAS'])->format('Y-m-d H:i:s');
        }


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
        if (Carbon::parse($transaksi->TANGGAL_PESAN)->diffInMinutes(Carbon::now()) > 1) {
            $transaksi->STATUS_TRANSAKSI = 'BATAL KARENA LAMA';
            return response()->json([
                'error' => 'Transaksi sudah lebih dari 1 menit, tidak dapat mengupload bukti bayar.'
            ], 400);
        }

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
        $pembeli = Pembeli::where('ID_PEMBELI', $transaksi->ID_PEMBELI)->first();
        $keranjang = Keranjang::where('ID_PEMBELI', $transaksi->ID_PEMBELI)->get();
        foreach ($keranjang as $item) {
            $item->delete();
        }
        if (is_array($produkList)) {
            foreach ($produkList as $produk) {
                // Contoh: menyimpan ke tabel detail_pembelian
                $produkModel = Produk::find($produk['KODE_PRODUK']);
                if ($produkModel) {
                    // Contoh: update kolom TERJUAL
                    $produkModel->ID_PEMBELIAN = $transaksi->ID_PEMBELIAN; // Set ID_PEMBELIAN pada produk
                    $produkModel->ID_PEMBELI = $transaksi->ID_PEMBELI; // Set ID_PEMBELI pada produk
                    $penitipan = TransaksiPenitipan::where('KODE_PRODUK', $produkModel->KODE_PRODUK)->first();
                    $penitipan->STATUS_PENITIPAN = 'Laku'; // Update status penitipan

                    // Contoh: update kolom STATUS jika null

                    $produkModel->save();
                    $penitipan->save();
                }
            }
        }
        return response()->json([
            'message' => 'Bukti bayar berhasil diupload',
            'path' => $transaksi->BUKTI_BAYAR,
        ]);
    }

    public function showDisiapkan()
    {
        $transaksi = TransaksiPembelian::with([
            'pegawai',
            'pembeli'
        ])
        ->where('STATUS_TRANSAKSI', 'Disiapkan')
        ->get();

        return response()->json($transaksi);
    }

    public function prosesKirim(Request $request, $id)
    {
        $transaksi = TransaksiPembelian::findOrFail($id);

        $rules = [
            'TANGGAL_AMBIL' => 'nullable|date'
        ];

        if ($transaksi->STATUS_PENGIRIMAN !== 'Pickup') {
            $rules['ID_PEGAWAI'] = 'required|exists:pegawai,ID_PEGAWAI';
        }

        $request->validate($rules);

        $pegawaiId = $request->ID_PEGAWAI;
        $tanggalPengambilan = $request->TANGGAL_AMBIL;

        $tanggalLunas = Carbon::parse($transaksi->TANGGAL_LUNAS);
        $hariIni = Carbon::now()->toDateString(); // Tanggal sistem saat ini

        if ($transaksi->STATUS_PENGIRIMAN === 'Pickup') {
            $transaksi->TANGGAL_AMBIL = $tanggalPengambilan;
            $transaksi->STATUS_TRANSAKSI = 'Diambil';
        } else {
            $transaksi->ID_PEGAWAI = $pegawaiId;
            $transaksi->STATUS_TRANSAKSI = 'Dikirim';
            // Logika pengiriman berdasarkan TANGGAL_LUNAS
            if ($tanggalLunas->toDateString() === $hariIni) {
                // Jika tanggal lunas adalah hari ini, periksa jamnya
                if ($tanggalLunas->hour >= 16) {
                    $transaksi->TANGGAL_KIRIM = $tanggalLunas->copy()->addDay()->toDateString(); // besok
                } else {
                    $transaksi->TANGGAL_KIRIM = $hariIni; // hari ini
                }
            } else {
                // Jika tanggal lunas bukan hari ini, default ke hari ini
                $transaksi->TANGGAL_KIRIM = $hariIni;
            }
        }

        $transaksi->save();

        return response()->json(['message' => 'Pengiriman berhasil diproses.']);
    }

    public function showDikirim()
    {
        $transaksi = TransaksiPembelian::with(['pegawai', 'pembeli'])
            ->whereIn('STATUS_TRANSAKSI', ['Dikirim', 'Diambil'])
            ->get();

        // Ambil semua transaksi yang perlu diupdate
        $hangusList = DB::table('transaksi_pembelian')
            ->where('STATUS_TRANSAKSI', 'Diambil')
            ->whereRaw("DATE(DATE_ADD(TANGGAL_AMBIL, INTERVAL 2 DAY)) <= CURDATE()")
            ->get();

        foreach ($hangusList as $item) {
            // Ubah status transaksi menjadi Hangus
            DB::table('transaksi_pembelian')
                ->where('ID_PEMBELIAN', $item->ID_PEMBELIAN)
                ->update(['STATUS_TRANSAKSI' => 'Hangus']);

            // Ambil semua produk yang terkait dengan pembelian ini
            $produkList = DB::table('produk')
                ->where('ID_PEMBELIAN', $item->ID_PEMBELIAN)
                ->get();

            foreach ($produkList as $produk) {
                // Ubah status penitipan menjadi "Barang untuk Donasi"
                DB::table('transaksi_penitipan')
                    ->where('KODE_PRODUK', $produk->KODE_PRODUK)
                    ->update(['STATUS_PENITIPAN' => 'Barang untuk Donasi']);
            }

            // Proses komisi, saldo, dan poin
            $this->prosesKomisiPembelian($item->ID_PEMBELIAN);
        }

        return response()->json($transaksi);
    }

    public function showSelesai()
    {
        $transaksi = TransaksiPembelian::with([
            'pegawai',
            'pembeli'
        ])
        ->whereIn('STATUS_TRANSAKSI', ['Selesai', 'Hangus', 'Dibatalkan'])
        ->get();

        return response()->json($transaksi);
    }

    public function produkByPembelian(Request $request)
    {
        $request->validate([
            'ID_PEMBELIAN' => 'required|integer',
        ]);

        $idPembelian = $request->ID_PEMBELIAN;

        // Query mengambil produk berdasarkan ID_PEMBELIAN
        // Contoh asumsi tabel produk dan foto_produk terhubung lewat KODE_PRODUK

        $produk = DB::table('produk')
            ->join('kategori_produk', 'produk.ID_KATEGORI', '=', 'kategori_produk.ID_KATEGORI')
            ->leftJoin('foto_produk', 'produk.KODE_PRODUK', '=', 'foto_produk.KODE_PRODUK')
            ->select(
                'produk.KODE_PRODUK',
                'produk.NAMA_PRODUK',
                'produk.ID_KATEGORI',
                'kategori_produk.NAMA_KATEGORI as KATEGORI',
                'produk.BERAT',
                'produk.GARANSI',
                'produk.HARGA',
                'foto_produk.ID_FOTO',
                'foto_produk.PATH_FOTO'
            )
            ->where('produk.ID_PEMBELIAN', $idPembelian) // pastikan kolom ini sesuai skema kamu
            ->get();

        // Group foto berdasarkan produk
        $produkGrouped = [];

        foreach ($produk as $row) {
            $kodeProduk = $row->KODE_PRODUK;
            if (!isset($produkGrouped[$kodeProduk])) {
                $produkGrouped[$kodeProduk] = [
                    'KODE_PRODUK' => $row->KODE_PRODUK,
                    'NAMA_PRODUK' => $row->NAMA_PRODUK,
                    'ID_KATEGORI' => $row->ID_KATEGORI,
                    'KATEGORI' => $row->KATEGORI,
                    'BERAT' => $row->BERAT,
                    'GARANSI' => $row->GARANSI,
                    'HARGA' => $row->HARGA,
                    'foto' => [],
                ];
            }
            if ($row->ID_FOTO) {
                $produkGrouped[$kodeProduk]['foto'][] = [
                    'ID_FOTO' => $row->ID_FOTO,
                    'PATH_FOTO' => $row->PATH_FOTO,
                ];
            }
        }

        // Kembalikan array produk dengan foto masing-masing
        return response()->json(array_values($produkGrouped));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'ID_PEMBELIAN' => 'required|integer|exists:transaksi_pembelian,ID_PEMBELIAN',
            'STATUS_TRANSAKSI' => 'required|string',
            'setTanggalSampai' => 'sometimes|boolean',
        ]);

        $idPembelian = $request->ID_PEMBELIAN;
        $statusTransaksi = $request->STATUS_TRANSAKSI;
        $setTanggalSampai = $request->get('setTanggalSampai', false);

        $pembelian = DB::table('transaksi_pembelian')->where('ID_PEMBELIAN', $idPembelian)->first();
        if (!$pembelian) {
            return response()->json(['error' => 'Data pembelian tidak ditemukan.'], 404);
        }

        $updateData = ['STATUS_TRANSAKSI' => $statusTransaksi];
        if ($setTanggalSampai) {
            $updateData['TANGGAL_SAMPAI'] = now();
        }

        DB::table('transaksi_pembelian')->where('ID_PEMBELIAN', $idPembelian)->update($updateData);

        // Jalankan proses komisi dan poin jika status sudah lunas dan pengiriman selesai
        if (strtolower($statusTransaksi) === 'selesai') {
            $this->prosesKomisiPembelian($idPembelian);
        }

        return response()->json([
            'message' => 'Status transaksi berhasil diupdate dan komisi diproses.',
        ]);
    }

    private function prosesKomisiPembelian($idPembelian)
    {
        Log::info('Memulai proses komisi untuk pembelian ' . $idPembelian);
        $pembelian = DB::table('transaksi_pembelian')->where('ID_PEMBELIAN', $idPembelian)->first();
        if (!$pembelian) return;

        $produkList = DB::table('produk')->where('ID_PEMBELIAN', $idPembelian)->get();
        $totalHarga = 0;

        foreach ($produkList as $produk) {
            $harga = $produk->HARGA;
            $totalHarga += $harga;

            $penitipan = DB::table('transaksi_penitipan')
                ->where('KODE_PRODUK', $produk->KODE_PRODUK)
                ->first();

            $pegawai = DB::table('pegawai')
                ->where('ID_PEGAWAI', $produk->ID_PEGAWAI)
                ->first();

            if (!$penitipan) continue;

            $tanggalPenitipan = Carbon::parse($penitipan->TANGGAL_PENITIPAN);
            $tanggalLunas = $pembelian->TANGGAL_LUNAS ? Carbon::parse($pembelian->TANGGAL_LUNAS) : null;

            $isSudahPerpanjang = strtolower($penitipan->STATUS_PERPANJANGAN) === 'sudah';
            $persentaseReUseMart = $isSudahPerpanjang ? 0.30 : 0.20;
            $komisiReUseMartAwal = $persentaseReUseMart * $harga;
            $komisiReUseMart = $komisiReUseMartAwal;

            $komisiHunter = 0;
            if (!empty($produk->ID_PEGAWAI) && $pegawai->ID_ROLE === 'RL003') {
                $komisiHunter = 0.05 * $harga;
                $komisiReUseMart -= $komisiHunter;
            }

            $bonusPenitip = 0;
            if ($tanggalLunas && $tanggalLunas->diffInDays($tanggalPenitipan) < 7) {
                $bonusPenitip = 0.10 * $komisiReUseMartAwal;
                $komisiReUseMart -= $bonusPenitip;
            }

            if ($komisiReUseMart < 0) {
                $komisiReUseMart = 0;
            }

            $pendapatanPenitip = ($harga - $komisiReUseMartAwal) + $bonusPenitip;

            if (!empty($penitipan->ID_PENITIP)) {
                DB::table('penitip')
                    ->where('ID_PENITIP', $penitipan->ID_PENITIP)
                    ->increment('SALDO_PENITIP', round($pendapatanPenitip));
            }

            DB::table('komisi')->insert([
                'ID_PEMBELIAN' => $idPembelian,
                'KODE_PRODUK' => $produk->KODE_PRODUK,
                'KOMISI_REUSEMART' => round($komisiReUseMart),
                'KOMISI_HUNTER' => round($komisiHunter),
                'BONUS_PENITIP' => round($bonusPenitip),
            ]);
        }

        $poinDiskon = $pembelian->POIN_DISKON ?? 0; // ambil nilai poin_diskon dari transaksi_pembelian, default 0
        $diskon = floor($poinDiskon / 100) * 10000; // setiap 100 poin diskon 10rb

        // total harga setelah diskon poin
        $totalHargaSetelahDiskon = max($totalHarga - $diskon, 0); // jangan sampai negatif

        // Hitung poin baru berdasarkan total harga setelah diskon
        $poin = floor($totalHargaSetelahDiskon / 10000);

        // Bonus 20% poin jika lebih dari 500 ribu (hitungan berdasarkan total sebelum diskon bisa juga, sesuaikan)
        if ($totalHargaSetelahDiskon > 500000) {
            $poin += floor($poin * 0.20);
        }

        if (!empty($pembelian->ID_PEMBELI)) {
            DB::table('pembeli')
                ->where('ID_PEMBELI', $pembelian->ID_PEMBELI)
                ->increment('POIN_PEMBELI', $poin);
        }
        Log::info('Memulai proses komisi untuk pembelian ' . $idPembelian);
    }

    public function generateId($idPembelian)
    {
        // Ambil tanggal sekarang atau tanggal lunas sesuai kebutuhan
        $tanggalLunasS = TransaksiPembelian::find($idPembelian); // pakai Carbon bawaan Laravel
        $tanggalLunasS = $tanggalLunasS->TANGGAL_PESAN;
        // dd($tanggalLunas);
        // Format tahun dan bulan
        $tanggalLunas = Carbon::parse($tanggalLunasS);
        $tahun = $tanggalLunas->format('Y');   // contoh: 2025
        $bulan = $tanggalLunas->format('m');   // contoh: 06

        // Gabungkan jadi nomor nota: tahun.bulan.ID_PEMBELIAN
        $nomorNota = $tahun . '.' . $bulan . '.' . $idPembelian;
        // dd($nomorNota);

        return $nomorNota;
    }

    public function findKonfirmasi()
    {
        $data = TransaksiPembelian::where('STATUS_TRANSAKSI', 'MENUNGGU KONFIRMASI')->get([
            'ID_PEMBELIAN', 'TOTAL_BAYAR', 'BUKTI_BAYAR','POIN_DISKON', 'ID_PEMBELI'
        ]);

        return response()->json($data);
    }

    public function konfirmasiI($id){
        $transaksi = TransaksiPembelian::find($id);
        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        $transaksi->STATUS_TRANSAKSI = "DISIAPKAN";

        return redirect()->back()->with('success', 'BERHASIL UBAH');
    }

    public function gagalKonfirmasiI($id){
        $transaksi = TransaksiPembelian::find($id);
        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        $transaksi->STATUS_TRANSAKSI = "GAGAL KONFIRMASI";


        return redirect()->back()->with('error', 'GAGAL UBAH STATUS TRANSAKSI!');
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
            $transaksi->TANGGAL_LUNAS = Carbon::now(); // Set tanggal lunas ke waktu sekarang
            $transaksi->save();

            ////////////////NOTIFFFFF
            $idPembelian = $transaksi->ID_PEMBELIAN; // misal kolom id
            $produkIds = Produk::where('ID_PEMBELIAN', $idPembelian)->pluck('KODE_PRODUK');

            $idPenitips = TransaksiPenitipan::whereIn('KODE_PRODUK', $produkIds)
                        ->pluck('ID_PENITIP')->unique();

            // $tokens = Penitip::whereIn('id', $idPenitips)
            //           ->whereNotNull('fcm_token')
            //           ->pluck('fcm_token')->toArray();

            // if (!empty($tokens)) {
            //     sendFcmNotification(
            //         $tokens,
            //         'Status Transaksi Update',
            //         'Barang Anda Sudah Terjual dan Sedang Disiapkan untuk Pengiriman',
            //         ['transaksi_id' => $transaksi->id]
            //     );
            // }

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
                    $hargaBayar = $hargaBayar - ($poinDigunakan*100);

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
        $produkList = Produk::where('ID_PEMBELIAN', $transaksi->ID_PEMBELIAN)->get();

        foreach ($produkList as $produk) {
            // Contoh: menyimpan ke tabel detail_pembelian
            $produkModel = Produk::find($produk['KODE_PRODUK']);
            if ($produkModel) {
                Keranjang::tambahProduk($transaksi->ID_PEMBELI, $produkModel->KODE_PRODUK);
                // Contoh: update kolom TERJUAL
                $produkModel->ID_PEMBELIAN = null; // Set ID_PEMBELIAN pada produk
                $penitipan = TransaksiPenitipan::where('KODE_PRODUK', $produkModel->KODE_PRODUK)->first();
                $penitipan->STATUS_PENITIPAN = 'Berlangsung'; // Update status penitipan

                // Contoh: update kolom STATUS jika null

                $produkModel->save();
                $penitipan->save();
            }
        }

        return response()->json(['message' => 'Transaksi telah DIBATALKAN']);
    }

    public function cetakNotaKurir($id_pembelian)
    {
        $transaksi = TransaksiPembelian::with(['produk.pegawai', 'pembeli', 'alamat', 'pegawai'])
            ->where('ID_PEMBELIAN', $id_pembelian)
            ->firstOrFail();

        if (!$transaksi->TANGGAL_LUNAS) {
            abort(400, 'Tanggal lunas belum ditentukan.');
        }

        $tanggalLunas = Carbon::parse($transaksi->TANGGAL_LUNAS);
        $no_nota = $tanggalLunas->format('y.m') . '.' . $transaksi->ID_PEMBELIAN;

        if (strtolower($transaksi->STATUS_PENGIRIMAN) === 'delivery') {
            $delivery = 'Kurir ReUseMart (' . ($transaksi->pegawai->NAMA_PEGAWAI ?? 'Nama Kurir Tidak Diketahui') . ')';
        } else {
            $delivery = '- (Diambil Sendiri)';
        }

        // Buat array produk
        $produkList = [];
        $totalHarga = 0;
        foreach ($transaksi->produk as $produk) {
            $produkList[] = [
                'nama_produk' => $produk->NAMA_PRODUK,
                'harga' => $produk->HARGA,
                'id_pegawai_qc' => $produk->pegawai->ID_PEGAWAI ?? null,
                'nama_pegawai_qc' => $produk->pegawai->NAMA_PEGAWAI ?? 'Petugas QC',
            ];
            $totalHarga += $produk->HARGA;
        }

        $nota = [
            'no_nota' => $no_nota,
            'tanggal_pesan' => $transaksi->TANGGAL_PESAN,
            'tanggal_lunas' => $transaksi->TANGGAL_LUNAS,
            'tanggal_kirim' => $transaksi->TANGGAL_KIRIM ?? '-',
            'tanggal_ambil' => $transaksi->TANGGAL_AMBIL ?? '-',
            'email_pembeli' => $transaksi->pembeli->EMAIL_PEMBELI ?? '-',
            'nama_pembeli' => $transaksi->pembeli->NAMA_PEMBELI ?? '-',
            'poin_pembeli' => $transaksi->pembeli->POIN_PEMBELI ?? 0,
            'alamat_pembeli' => $transaksi->alamat->LOKASI,
            'delivery' => $delivery,
            'poin_diskon' => $transaksi->POIN_DISKON ?? 0,
            'produk_list' => $produkList,
            'total_harga' => $totalHarga,
            'total_bayar' => $transaksi->TOTAL_BAYAR ?? 0,
        ];

        $pdf = Pdf::loadView('pegawai_gudang.nota_penjualan_kurir', compact('nota'));
        return $pdf->stream('nota_' . $no_nota . '.pdf');
    }

    public function laporanPenjualan()
    {
        // Query untuk mendapatkan laporan penjualan
        $penjualan = TransaksiPembelian::selectRaw('
                MONTH(TANGGAL_LUNAS) as bulan,
                COUNT(*) as jumlah_barang_terjual,   -- Menghitung jumlah transaksi (barang terjual)
                SUM(produk.HARGA) as jumlah_penjualan_kotor
            ')
            ->join('produk', 'produk.ID_PEMBELIAN', '=', 'transaksi_pembelian.ID_PEMBELIAN')  // Menghubungkan ke tabel produk
            ->whereYear('TANGGAL_LUNAS', 2025) // Filter berdasarkan tahun 2025
            ->groupBy('bulan') // Mengelompokkan berdasarkan bulan
            ->orderBy('bulan') // Mengurutkan berdasarkan bulan
            ->get();

        return view('owner.cetak_penjualan_bulanan', compact('penjualan'));
    }

    public function laporanPenjualan_pdf()
    {
        // Query untuk mendapatkan laporan penjualan
        $penjualan = TransaksiPembelian::selectRaw('
                MONTH(TANGGAL_LUNAS) as bulan,
                COUNT(*) as jumlah_barang_terjual,   -- Menghitung jumlah transaksi (barang terjual)
                SUM(produk.HARGA) as jumlah_penjualan_kotor
            ')
            ->join('produk', 'produk.ID_PEMBELIAN', '=', 'transaksi_pembelian.ID_PEMBELIAN')  // Menghubungkan ke tabel produk
            ->whereYear('TANGGAL_LUNAS', 2025) // Filter berdasarkan tahun 2025
            ->groupBy('bulan') // Mengelompokkan berdasarkan bulan
            ->orderBy('bulan') // Mengurutkan berdasarkan bulan
            ->get();

        $pdf = \PDF::loadView('owner.cetak_penjualan_bulanan', compact('penjualan'));
        return $pdf->download('laporan_penjualan_bulanan.pdf');
    }

    public function laporanKomisi()
    {
        // Mengambil data transaksi pembelian dengan relasi produk, komisi, dan transaksi penitipan
        $transaksiPembelian = TransaksiPembelian::with(['produk', 'komisi', 'transaksiPenitipan'])
                                                ->whereNotNull('TANGGAL_LUNAS') // Filter hanya yang sudah lunas
                                                ->get();

        // Mengelompokkan transaksi pembelian berdasarkan bulan TANGGAL_LUNAS
        $transaksiPembelianByMonth = $transaksiPembelian->groupBy(function ($item) {
            // Mengelompokkan berdasarkan bulan dari TANGGAL_LUNAS
            return Carbon::parse($item->TANGGAL_LUNAS)->format('F Y'); // Menggunakan nama bulan dan tahun (misalnya Januari 2025)
        });

        // Mengirim data ke view
        return view('owner.cetak_komisi_bulanan', compact('transaksiPembelianByMonth'));
    }

    public function laporanKomisi_pdf()
    {
        // Mengambil data transaksi pembelian dengan relasi produk, komisi, dan transaksi penitipan
        $transaksiPembelian = TransaksiPembelian::with(['produk', 'komisi', 'transaksiPenitipan'])
                                                ->whereNotNull('TANGGAL_LUNAS') // Filter hanya yang sudah lunas
                                                ->get();

        // Mengelompokkan transaksi pembelian berdasarkan bulan TANGGAL_LUNAS
        $transaksiPembelianByMonth = $transaksiPembelian->groupBy(function ($item) {
            // Mengelompokkan berdasarkan bulan dari TANGGAL_LUNAS
            return Carbon::parse($item->TANGGAL_LUNAS)->format('F Y'); // Menggunakan nama bulan dan tahun (misalnya Januari 2025)
        });


        $pdf = \PDF::loadView('owner.cetak_komisi_bulanan_pdf', compact('transaksiPembelianByMonth'));
        return $pdf->download('laporan_komisi_bulanan.pdf');
    }

    public function laporanKomisi_pdf_bulan(Request $request)
    {
        // Mengambil tahun dan bulan yang dipilih dari parameter GET
        $tahun = $request->input('year');
        $bulan = $request->input('month');

        // Mengambil data transaksi pembelian dengan relasi produk, komisi, dan transaksi penitipan
        $transaksiPembelian = TransaksiPembelian::with(['produk', 'komisi', 'transaksiPenitipan'])
                                                ->whereNotNull('TANGGAL_LUNAS') // Filter hanya yang sudah lunas
                                                ->whereYear('TANGGAL_LUNAS', $tahun) // Filter berdasarkan tahun
                                                ->whereMonth('TANGGAL_LUNAS', $bulan) // Filter berdasarkan bulan
                                                ->get();

        // Mengelompokkan transaksi pembelian berdasarkan bulan TANGGAL_LUNAS
        $transaksiPembelianByMonth = $transaksiPembelian->groupBy(function ($item) {
            return Carbon::parse($item->TANGGAL_LUNAS)->format('F Y'); // Menggunakan nama bulan dan tahun
        });

        // Memuat tampilan dan mendownload PDF
        $pdf = \PDF::loadView('owner.cetak_komisi_bulanan_pdf', compact('transaksiPembelianByMonth'));
        return $pdf->download('laporan_komisi_bulanan_' . $tahun . '_' . $bulan . '.pdf');
    }

    //Mobile
    public function indexMobile(Request $request)
    {
        // Validate that the buyer's ID is provided
        $request->validate([
            'ID_PEMBELI' => 'required|exists:pembeli,ID_PEMBELI',
        ]);

        // Fetch the transaction history for the given Pembeli
        $transaksi = TransaksiPembelian::where('ID_PEMBELI', $request->ID_PEMBELI)
            ->with(['produk', 'pegawai', 'alamat', 'komisi']) // Eager load relations
            ->orderBy('TANGGAL_PESAN', 'desc') // Order by date of order
            ->get();

        // Return the result as JSON
        return response()->json($transaksi);
    }

    // Method to get the details of a specific transaction - Ends with 'Mobile'
    public function showMobile($id)
    {
        // Fetch the transaction details by ID
        $transaksi = TransaksiPembelian::with(['produk', 'pegawai', 'alamat', 'komisi', 'transaksiPenitipan'])
            ->where('ID_PEMBELIAN', $id)
            ->first();

        // Check if the transaction exists
        if (!$transaksi) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Return the transaction details as JSON
        return response()->json($transaksi);
    }

    public function findKurir($id)
    {
        $transaksi = TransaksiPembelian::with('alamat', 'pembeli')
            ->where('ID_PEGAWAI', $id)
            ->where('STATUS_PENGIRIMAN', 'delivery')
            ->where('STATUS_TRANSAKSI', 'DIKIRIM')
            ->get();

        if (!$transaksi) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        return response()->json($transaksi);
    }
    public function findKurirHistory($id)
    {
        $transaksi = TransaksiPembelian::with('alamat', 'pembeli')
            ->where('ID_PEGAWAI', $id)
            ->where('STATUS_PENGIRIMAN', 'delivery')
            // ->where('STATUS_TRANSAKSI', 'SELESAI')
            ->get();

        if (!$transaksi) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        return response()->json($transaksi);
    }

    public function selesaiKurir($id){
        $transaksi = TransaksiPembelian::find($id);
        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        $transaksi->STATUS_TRANSAKSI = 'SELESAI';
        $transaksi->TANGGAL_SAMPAI = now();

        $transaksi->save();

        $produk = Produk::where('ID_PEMBELIAN', $transaksi->ID_PEMBELIAN)->first();
        $tpen = TransaksiPenitipan::where('KODE_PRODUK', $produk->KODE_PRODUK)->first();

        Notifikasi::createNotifikasi([
            'ID_PEMBELI' => $transaksi->ID_PEMBELI,
            'ISI' => 'Pesanan Anda :'.$produk->NAMA_PRODUK. ' telah sampai di alamat : '.$transaksi->alamat->LOKASI,
            'TANGGAL' => \Carbon\Carbon::now(),
            'JUDUL' => 'Pembelian ReuseMart sudah sampai'
        ]);

        Notifikasi::createNotifikasi([
            'ID_PENITIP' => $tpen->ID_PENITIP,
            'ISI' => 'Barang Anda :'.$produk->NAMA_PRODUK. ' telah dibeli dan sampai di pembeli : '.$transaksi->pembeli->NAMA_PEMBELI,
            'TANGGAL' => \Carbon\Carbon::now(),
            'JUDUL' => 'Penjualan Barang di ReuseMart telah berhasil'
        ]);



        // event(new notifSelesai($transaksi->ID_PEMBELI, $tpen->ID_PENITIP, 'TRANSAKSI PEMBELIAN KAMU SELESAI'));

        return response()->json([
            'success' => true,
            'message' => 'Pengiriman Berhasil'
        ], 200);
    }

    public function leaderboardMobile(Request $request)
    {
        try {
            // Ambil bulan dan tahun dari request (optional, default jika tidak ada)
            $bulan = $request->input('bulan'); // Misal bulan 2
            $tahun = $request->input('tahun'); // Misal tahun 2025

            // Query untuk mengambil leaderboard berdasarkan transaksi pembelian dan penitip
            $leaderboardQuery = TransaksiPembelian::selectRaw('penitip.ID_PENITIP, penitip.NAMA_PENITIP, SUM(transaksi_pembelian.TOTAL_BAYAR) as TOTAL_BAYAR')
                ->join('produk', 'produk.ID_PEMBELIAN', '=', 'transaksi_pembelian.ID_PEMBELIAN')
                ->join('transaksi_penitipan', 'transaksi_penitipan.KODE_PRODUK', '=', 'produk.KODE_PRODUK')
                ->join('penitip', 'penitip.ID_PENITIP', '=', 'transaksi_penitipan.ID_PENITIP')
                ->where('transaksi_pembelian.STATUS_TRANSAKSI', 'SELESAI'); // Hanya transaksi yang selesai

            // Filter berdasarkan bulan dan tahun jika diberikan
            if ($bulan && $tahun) {
                $leaderboardQuery->whereYear('transaksi_pembelian.TANGGAL_LUNAS', $tahun)
                                 ->whereMonth('transaksi_pembelian.TANGGAL_LUNAS', $bulan);
            }

            // Ambil data leaderboard
            $leaderboard = $leaderboardQuery
                ->groupBy('penitip.ID_PENITIP', 'penitip.NAMA_PENITIP')
                ->orderByDesc('TOTAL_BAYAR') // Urutkan berdasarkan total bayar tertinggi
                ->get();

            // Cek jika data kosong
            if ($leaderboard->isEmpty()) {
                return response()->json(['message' => 'Tidak ada penitip yang memiliki penjualan.']);
            }

            // Mengembalikan data leaderboard
            return response()->json($leaderboard);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
}
