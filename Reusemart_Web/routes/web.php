<?php
use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Illuminate\Support\Facades\Broadcast;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardOwnerController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\TransaksiPenitipanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TransaksiPembelianController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\DiskusiProdukController;
use App\Http\Controllers\PenukaranController;

use App\Models\Penitip;
use App\Models\TransaksiPenitipan;

Route::get('/login', function () {
    return view('login.login');
})->name('login');

Route::get('/kelola_penitip', function () {
    return view('penitip.kelola_penitip');
})->name('kelolaPenitip');

Route::get('/create_penitip', function () {
    return view('penitip.create_penitip');
})->name('createPenitip');

Route::get('/update_penitip/{id}', function ($id) {
    return view('penitip.update_penitip', ['id_penitip' => $id]);
})->name('updatePenitip');

Route::get('/profile/pembeli', function () {
    return view('pembeli.profile_pembeli');
})->name('profilePembeli');

Route::get('/history/pembelian', function () {
    return view('pembeli.history_pembelian');
})->name('historyPembelian');

Route::get('/penitip/penitipan', function () {
    return view('transaksi_penitipan_penitip.show_penitipan');
})->name('showPenitipan');

Route::get('/pegawai_gudang/pilih_transaksi', function () {
    return view('pegawai_gudang.show_pilih_transaksi');
})->name('showPilihTransaksi');

Route::get('/pegawai_gudang/show_transaksi_pembelian', function () {
    return view('pegawai_gudang.show_transaksi_pembelian');
})->name('showTransaksiPembelian');

Route::get('/owner/laporan_kategori_per_tahun', [TransaksiPenitipanController::class, 'cetakLaporanKategoriPerTahun']);
Route::get('/owner/laporan_barang_penitipan_habis', [TransaksiPenitipanController::class, 'cetakProdukExpired']);

Route::get('/owner/laporan_kategori_per_tahun_pdf', [TransaksiPenitipanController::class, 'cetakLaporanKategoriPerTahunPdf']);
Route::get('/owner/laporan_barang_penitipan_habis_pdf', [TransaksiPenitipanController::class, 'cetakProdukExpiredPdf']);

Route::get('/tentang-kami', function () { return view('general.tentang_kami');});

Route::get('/foto/{folder}/{filename}', function ($folder, $filename) {
    $allowedFolders = ['foto_kategori', 'foto_produk', 'icon', 'images', 'merch'];

    if (!in_array($folder, $allowedFolders)) {
        abort(404);
    }

    $path = public_path("$folder/$filename");

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    return response($file, 200)->header("Content-Type", $type);
});

//KEVIN===============================================================================================================

   //registrasi
Route::get('/registrasi', function () {
    return view('registrasi.registrasi');
})->name('registrasi');
Route::get('/registrasi/pembeli', function () {
    return view('registrasi.registrasi_pembeli');
});
Route::get('/registrasi/organisasi', function () {
    return view('registrasi.registrasi_organisasi');
});

    //forgot password
Route::get('/forgot-password', function () {
    return view('login.forgot_password');
})->name('forgot-password');

Route::post('/reset-password-request', [LoginController::class, 'resetPassword'])->name('reset.password.request');

Route::get('/reset-password-customer', [LoginController::class, 'showResetForm'])->name('reset.password.form');
Route::post('/reset-password-customer', [LoginController::class, 'updatePassword'])->name('reset.password.update');

    //profile penitip
Route::get('/profile/penitip', function () {
    return view('penitip.profile_penitip');
})->name('profilePenitip');

// histori punya penitip
Route::get('/penitip/histori', [PenitipController::class, 'history_produk'])->name('historiPenitip');

//diskusi
Route::post('/diskusi/store', [DiskusiProdukController::class, 'store'])->name('diskusi.store');

//keranjang
    Route::post('/keranjang/store/{KODE_PRODUK}', [KeranjangController::class, 'store'])->name('keranjang.store');
    Route::delete('/keranjang/delete/{KODE_PRODUK}', [KeranjangController::class, 'delete'])->name('keranjang.delete');
    Route::get('/keranjang/check/{kodeProduk}', [KeranjangController::class, 'checkInKeranjang']);
    // Route::get('/keranjang/{idPembeli}', [KeranjangController::class, 'findByIdPembeli'])->name('keranjang.findByIdPembeli');

Route::get('/keranjang', function () {
    return view('produk.keranjang');
})->name('produk.keranjang');


//PEMBELI

Route::get('/pembeli/dashboard', function () {
    return view('pembeli.dashboard');
})->name('pembeli.dashboard');

Route::post('/transaksi-pembelian', [TransaksiPembelianController::class, 'store'])->name('transaksi-pembelian.store');

Route::get('/bukti-bayar', [TransaksiPembelianController::class, 'buktiBayar'])->name('bukti-bayar');
// Route::get('/upload-bukti', [PembayaranController::class, 'uploadBukti'])->name('bukti-bayar-upload');

Route::get('/upload-bukti', function () {
    return view('produk.bukti_bayar'); // ini sesuai dengan nama file blade kamu
})->name('bukti_bayar');

Route::get('/keranjang/transaksi_pembelian', function () {
    return view('produk.konfirmasi_pembelian'); // ini sesuai dengan nama file blade kamu
})->name('konfirmasi_pembelian');
// Route::post('/transaksi-pembelian/konfirmasi/{id}', [TransaksiPembelianController::class, 'konfirmasi']);
// Route::post('/transaksi-pembelian/gagal/{id}', [TransaksiPembelianController::class, 'gagalKonfirmasi']);

Route::get('/nota/kurir/{id}', [TransaksiPembelianController::class, 'cetakNotaKurir'])
    ->name('nota.kurir');

Route::put('/upload-bukti/{id}', [TransaksiPembelianController::class, 'buktiBayar'])->name('uploadBuktiBayar');


// Route::get('/upload-bukti', function () {
//     return view('produk.bukti_bayar');
// })->name('bukti-bayar-upload');
//KEVIN===============================================================================================================


//RAFI===============================================================================================================

//produk
Route::get('/', [TransaksiPenitipanController::class, 'index']);
Route::get('/show', [TransaksiPenitipanController::class, 'index']);
Route::get('/produk/{kode_produk}', [ProdukController::class, 'show']);
Route::post('/produk', [ProdukController::class, 'store']);
Route::put('/produk/{id}', [ProdukController::class, 'update']);
Route::delete('/produk/{id}', [ProdukController::class, 'destroy']);

//pegawai gudang soal produk
Route::post('/produk/store', [ProdukController::class, 'store'])->name('produk.store');
Route::get('/pegawai_gudang/create_produk', [ProdukController::class, 'create'])->name('produk.create_produk');
Route::get('/pegawai_gudang/show_produk', [ProdukController::class, 'tampil'])->name('pegawai_gudang.show_produk');
Route::get('/pegawai_gudang/update_produk/{kode_produk}', [ProdukController::class, 'edit'])->name('pegawai_gudang.update_produk');
Route::delete('/pegawai_gudang/delete_produk/{kode_produk}', [ProdukController::class, 'delete'])->name('pegawai_gudang.delete_produk');
Route::put('/pegawai_gudang/update_produk/{kode_produk}', [ProdukController::class, 'update'])->name('pegawai_gudang.update_produk');

//pegawai gudang soal transaksi penitipan
Route::get('/pegawai_gudang/create_transaksi_penitipan', [TransaksiPenitipanController::class, 'create'])->name('pegawai_gudang.create_transaksi_penitipan');
Route::post('/pegawai_gudang/store_transaksi_penitipan', [TransaksiPenitipanController::class, 'store'])->name('pegawai_gudang.store_transaksi_penitipan');
Route::get('/pegawai_gudang/show_transaksi_penitipan', [TransaksiPenitipanController::class, 'index2'])->name('pegawai_gudang.show_transaksi_penitipan');
Route::get('/pegawai_gudang/update_transaksi_penitipan/{id}', [TransaksiPenitipanController::class, 'update_transaksi_penitipan'])->name('pegawai_gudang.update_transaksi_penitipan');
Route::delete('/pegawai_gudang/delete/{id}', [TransaksiPenitipanController::class, 'delete'])->name('pegawai_gudang.delete_transaksi_penitipan');
Route::get('/pegawai_gudang/update_transaksi_penitipan/{id}', [TransaksiPenitipanController::class, 'edit'])->name('pegawai_gudang.edit_transaksi_penitipan');
Route::put('/pegawai_gudang/update_transaksi_penitipan/{id}', [TransaksiPenitipanController::class, 'update'])->name('pegawai_gudang.update_transaksi_penitipan');
Route::put('/transaksi-penitipan/{id}/diambil', [TransaksiPenitipanController::class, 'markAsTaken'])->name('pegawai_gudang.mark_as_taken');
Route::get('/cetak-nota/{id}', [TransaksiPenitipanController::class, 'printNota'])->name('pegawai_gudang.print_nota');

//admin
Route::get('/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');
Route::post('/pegawai/store', [PegawaiController::class, 'store'])->name('pegawai.store');
Route::get('/admin/dashboard', [DashboardAdminController::class, 'index']);
Route::put('pegawai/update/{id}', [PegawaiController::class, 'update']);
Route::match(['get', 'put'], 'pegawai/update/{id}', [PegawaiController::class, 'update']);
Route::delete('pegawai/delete/{id}', [PegawaiController::class, 'destroy']);

//owner
Route::get('/owner/dashboard', [DashboardOwnerController::class, 'index']);
Route::get('/owner/history_donasi', [DashboardOwnerController::class, 'showHistory']);

//owner (laporan)
Route::get('/owner/laporan', function () {
    $penitip = Penitip::whereHas('transaksiPenitipan')->get();
    return view('owner.laporan',compact('penitip'));
});
Route::get('/owner/cetak_penjualan_bulanan', [TransaksiPembelianController::class, 'laporanPenjualan']);
Route::get('/owner/cetak_penjualan_bulanan_pdf', [TransaksiPembelianController::class, 'laporanPenjualan_pdf']);

Route::get('/owner/cetak_komisi_bulanan', [TransaksiPembelianController::class, 'laporanKomisi']);
Route::get('/owner/cetak_komisi_bulanan_pdf', [TransaksiPembelianController::class, 'laporanKomisi_pdf']);
Route::get('/owner/cetak_komisi_bulanan_pdf_bulan', [TransaksiPembelianController::class, 'laporanKomisi_pdf_bulan'])->name('cetak_komisi_bulanan_pdf_bulan');

Route::get('/owner/cetak_stok_gudang', [TransaksiPenitipanController::class, 'cetakStokGudang']);
Route::get('/owner/cetak_stok_gudang_pdf', [TransaksiPenitipanController::class, 'cetakStokGudang_pdf']);

//PDF KEVIN
Route::get('/owner/cetak_donasi_barang', [TransaksiPenitipanController::class, 'cetakDonasiBarang']);
Route::get('/owner/cetak_donasi_barang_pdf', [TransaksiPenitipanController::class, 'cetakDonasiBarangPDF']);
Route::get('/owner/cetak_request_donasi', [TransaksiPenitipanController::class, 'cetakRequestDonasi']);
Route::get('/owner/cetak_request_donasi_pdf', [TransaksiPenitipanController::class, 'cetakRequestDonasiPDF']);
Route::get('/owner/cetak_transaksi_penitip', [TransaksiPenitipanController::class, 'cetakTransaksiPenitip']);
Route::get('/owner/cetak_transaksi_penitip_pdf', [TransaksiPenitipanController::class, 'cetakTransaksiPenitipPDF']);



//history transaksi + rating
Route::get('history-transaksi-pembelian', [TransaksiPembelianController::class, 'history'])->name('transaksi_pembelian.history');
Route::get('transaksi-pembelian/{id}', [TransaksiPembelianController::class, 'show'])->name('transaksi_pembelian.show');
Route::post('transaksi-pembelian/{id}/rating', [TransaksiPembelianController::class, 'rating'])->name('transaksi_pembelian.rating');

//merchandise
Route::get('/penukaran/show', [PenukaranController::class, 'index'])->name('penukaran.show');
Route::get('/penukaran/sudah-diambil', [PenukaranController::class, 'filterSudahDiambil'])->name('penukaran.sudahDiambil');
Route::get('/penukaran/belum-diambil', [PenukaranController::class, 'filterBelumDiambil'])->name('penukaran.belumDiambil');
Route::get('/penukaran/{id}/edit', [PenukaranController::class, 'edit'])->name('penukaran.edit');
Route::put('/penukaran/{id}', [PenukaranController::class, 'update'])->name('penukaran.update');
Route::delete('/penukaran/{id}', [PenukaranController::class, 'destroy'])->name('penukaran.destroy');
//RAFI===============================================================================================================


Broadcast::routes();
