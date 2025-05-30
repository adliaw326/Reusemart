<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardOwnerController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\TransaksiPenitipanController;
use App\Http\Controllers\TransaksiPembelianController;

Route::get('/', function () {
    return view('login.login');
})->name('login');

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

Route::get('/tentang-kami', function () { return view('general.tentang_kami');});

//Kevin
Route::get('/registrasi', function () {
    return view('general/registrasi');
})->name('registrasi');
Route::get('/registrasi/pembeli', function () {
    return view('general/registrasi_pembeli');
});
Route::get('/registrasi/organisasi', function () {
    return view('general/registrasi_organisasi');
});

Route::get('/show', [TransaksiPenitipanController::class, 'index']);
Route::get('/produk/{kode_produk}', [ProdukController::class, 'show']);
Route::post('/produk', [ProdukController::class, 'store']);
Route::put('/produk/{id}', [ProdukController::class, 'update']);
Route::delete('/produk/{id}', [ProdukController::class, 'destroy']);

Route::get('/admin/dashboard', [DashboardAdminController::class, 'index']);
Route::get('/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');
Route::post('/pegawai/store', [PegawaiController::class, 'store'])->name('pegawai.store');
Route::put('pegawai/update/{id}', [PegawaiController::class, 'update']);
Route::delete('pegawai/delete/{id}', [PegawaiController::class, 'destroy']);
Route::match(['get', 'put'], 'pegawai/update/{id}', [PegawaiController::class, 'update']);
Route::post('/pegawai/store', [PegawaiController::class, 'store'])->name('pegawai.store');
Route::get('/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');

Route::get('/owner/history_donasi', [DashboardOwnerController::class, 'showHistory']);

Route::get('history-transaksi-pembelian', [TransaksiPembelianController::class, 'history'])->name('transaksi_pembelian.history');
Route::get('transaksi-pembelian/{id}', [TransaksiPembelianController::class, 'show'])->name('transaksi_pembelian.show');
Route::post('transaksi-pembelian/{id}/rating', [TransaksiPembelianController::class, 'rating'])->name('transaksi_pembelian.rating');