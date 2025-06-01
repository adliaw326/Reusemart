<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPembelian;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Pembeli;
use App\Models\TransaksiPenitipan;
use App\Models\Penitip;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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

            // Mendapatkan rating rata-rata produk
            $currentAverageRating = $produk->RATING_RATA_RATA_P;

            // Mendapatkan total barang terjual produk
            $currentTotalBarangTerjual = $produk->TOTAL_BARANG_TERJUAL;

            // Menghitung rating baru dan rata-rata rating baru untuk produk
            $newRating = $request->input('rating');
            $averageRating = ($currentRating + $newRating) / 2;

                // Update rating produk dengan rata-rata baru
                $produk->RATING = $averageRating;
                $produk->save();

            // Temukan transaksi penitipan berdasarkan KODE_PRODUK yang menghubungkan produk dengan penitip
            $transaksiPenitipan = TransaksiPenitipan::where('KODE_PRODUK', $produk->KODE_PRODUK)->first();

            // Temukan penitip yang terkait dengan transaksi penitipan
            if ($transaksiPenitipan) {
                $penitip = Penitip::find($transaksiPenitipan->ID_PENITIP);

                if ($penitip) {
                    // Jika TOTAL_BARANG_TERJUAL adalah NULL, langsung tambahkan rating rata-rata penitip
                    if (is_null($penitip->TOTAL_BARANG_TERJUAL)) {
                        $penitip->RATING_RATA_RATA_P = $newRating;
                        $penitip->TOTAL_BARANG_TERJUAL = 1;
                    } else {
                        // Mendapatkan rating rata-rata penitip dan total barang terjual penitip
                        $currentPenitipRating = $penitip->RATING_RATA_RATA_P;
                        $currentPenitipTotalBarang = $penitip->TOTAL_BARANG_TERJUAL;

                        // Menghitung rating rata-rata penitip yang baru
                        $newPenitipTotalBarang = $currentPenitipTotalBarang + 1;
                        $newPenitipRating = (($currentPenitipRating * $currentPenitipTotalBarang) + $newRating) / $newPenitipTotalBarang;

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

    public function buktiBayar($id)
    {
        $transaksi = TransaksiPembelian::findOrFail($id);

        // Jika ingin juga ambil data relasi (misal produk), bisa gunakan with()
        // $transaksi = TransaksiPembelian::with('produk')->findOrFail($id);

        return view('produk.bukti_bayar', compact('transaksi'));
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

            if (!$penitipan) continue;

            $tanggalPenitipan = Carbon::parse($penitipan->TANGGAL_PENITIPAN);
            $tanggalLunas = $pembelian->TANGGAL_LUNAS ? Carbon::parse($pembelian->TANGGAL_LUNAS) : null;

            $isSudahPerpanjang = strtolower($penitipan->STATUS_PERPANJANGAN) === 'sudah';
            $persentaseReUseMart = $isSudahPerpanjang ? 0.30 : 0.20;
            $komisiReUseMartAwal = $persentaseReUseMart * $harga;
            $komisiReUseMart = $komisiReUseMartAwal;

            $komisiHunter = 0;
            if (!empty($produk->ID_PEGAWAI)) {
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
}
