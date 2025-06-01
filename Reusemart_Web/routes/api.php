<?php

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PenitipPegawaiController;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\UserDataController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\TransaksiPembelianController;
use App\Http\Controllers\TransaksiPenitipanController;
use App\Http\Controllers\ProdukPenitipanController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/penitip', [PenitipPegawaiController::class, 'index']);
    Route::post('/get-user-data', [UserDataController::class, 'getUserData']);
    Route::get('/penitip/search', [PenitipPegawaiController::class, 'search']);
    Route::post('/penitip/create', [PenitipPegawaiController::class, 'store']);
    Route::get('/penitip/{id}', [PenitipPegawaiController::class, 'show']);
    Route::put('/penitip/{id}', [PenitipPegawaiController::class, 'update']);
    Route::delete('/penitip/{id}', [PenitipPegawaiController::class, 'destroy']);
    Route::post('/transaksi-penitipan-berlangsung', [TransaksiPenitipanController::class, 'getTransaksiBerlangsung']);
    Route::post('/perpanjang-penitipan', [TransaksiPenitipanController::class, 'perpanjangWaktu']);
    Route::post('/ambil-penitipan', [TransaksiPenitipanController::class, 'ambilPenitipan']);
    Route::post('/produk-by-penitipan', [ProdukPenitipanController::class, 'detailByPenitipan']);
});

// ORGANISASI
Route::post('/organisasi/register', [OrganisasiController::class, 'register']);

//PEMBELI
Route::post('/pembeli/register', [PembeliController::class, 'register']);
Route::post('/pembeli/show/{id}', [PembeliController::class, 'show'])->name('pembeli.show');

    Route::post('/keranjang/store/{ID_PEMBELI}/{KODE_PRODUK}', [KeranjangController::class, 'store'])->name('keranjang.store');
    Route::delete('/keranjang/delete/{ID_PEMBELI}/{KODE_PRODUK}', [KeranjangController::class, 'destroy'])->name('keranjang.delete');
    Route::get('/keranjang/check/{ID_PEMBELI}/{kodeProduk}', [KeranjangController::class, 'checkInKeranjang']);
    Route::get('/keranjang/{idPembeli}', [KeranjangController::class, 'findByIdPembeli'])->name('keranjang.findByIdPembeli');

//ALAMAT

    Route::get('/alamat/{ID_PEMBELI}', [AlamatController::class, 'find'])->name('alamat.find');

//TRANSAKSI PEMBELIAN
Route::post('/transaksi-pembelian', [TransaksiPembelianController::class, 'store'])->name('transaksi-pembelian.store');
// Route khusus PENITIP
// Route::middleware(['auth:penitip'])->group(function () {
//     Route::get('/penitip/profile', [PenitipController::class, 'profile']);
//     Route::get('/penitip/histori', [PenitipController::class, 'history_produk']);
// });

// // Route khusus PEMBELI
// Route::middleware(['auth:pembeli'])->group(function () {
//     Route::get('/pembeli/profile', [PembeliController::class, 'profile']);
// });

// // Route khusus ORGANISASI
// Route::middleware(['auth:organisasi'])->group(function () {
//     Route::get('/organisasi/profile', [OrganisasiController::class, 'profile']);
// });

// // Route khusus PEGAWAI
// Route::middleware(['auth:pegawai'])->group(function () {
//     Route::get('/pegawai/dashboard', [PegawaiController::class, 'dashboard']);
// });
