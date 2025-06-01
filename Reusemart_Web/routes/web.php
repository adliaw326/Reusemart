<?php

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

use App\Models\Penitip;

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

Route::get('/tentang-kami', function () { return view('general.tentang_kami');});
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

Route::get('/bukti-bayar/{id}', [TransaksiPembelianController::class, 'buktiBayar'])->name('bukti.bayar');
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
Route::get('/owner/history_donasi', [DashboardOwnerController::class, 'showHistory']);

//history transaksi + rating
Route::get('history-transaksi-pembelian', [TransaksiPembelianController::class, 'history'])->name('transaksi_pembelian.history');
Route::get('transaksi-pembelian/{id}', [TransaksiPembelianController::class, 'show'])->name('transaksi_pembelian.show');
Route::post('transaksi-pembelian/{id}/rating', [TransaksiPembelianController::class, 'rating'])->name('transaksi_pembelian.rating');
//RAFI===============================================================================================================
