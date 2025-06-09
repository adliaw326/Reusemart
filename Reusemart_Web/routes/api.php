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
use App\Http\Controllers\PegawaiController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\MerchController;
use App\Http\Controllers\HunterController;

Route::post('/run-check-transactions', function () {
    Artisan::call('transactions:check-pending');

    return response()->json([
        'message' => 'Command executed',
        'output' => Artisan::output()
    ]);
});


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/produk', [ProdukController::class, 'index']);
Route::get('/merch', [MerchController::class, 'index']);
Route::post('/penukaran', [MerchController::class, 'store']);

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
    Route::post('/transaksi-pembelian/disiapkan', [TransaksiPembelianController::class, 'showDisiapkan']);
    Route::post('/transaksi-pembelian/dikirim', [TransaksiPembelianController::class, 'showDikirim']);
    Route::post('/transaksi-pembelian/selesai', [TransaksiPembelianController::class, 'showSelesai']);
    Route::post('/kirim-transaksi/{id}', [TransaksiPembelianController::class, 'prosesKirim']);
    Route::get('/pegawai-by-role/{id_role}', [PegawaiController::class, 'getByRole']);
    Route::post('/produk-by-pembelian', [TransaksiPembelianController::class, 'produkByPembelian']);
    Route::post('/transaksi-pembelian/update-status', [TransaksiPembelianController::class, 'updateStatus']);

    //Mobile------------------------------------------------------------------------------------------------------------------->$_COOKIE
    Route::get('/profile/mobile', [PembeliController::class, 'showProfileMobile']);
    Route::get('/transaksi-pembelian/mobile', [TransaksiPembelianController::class, 'indexMobile']);
    Route::get('/transaksi-pembelian/{id}/mobile', [TransaksiPembelianController::class, 'showMobile']);

    Route::get('/penitip/profile/mobile', [PenitipController::class, 'showProfileMobile']);
    Route::get('/transaksi-penitipan/mobile', [TransaksiPenitipanController::class, 'indexMobile']);
    Route::get('/transaksi-penitipan/{id}/mobile', [TransaksiPenitipanController::class, 'showMobile']);

    Route::get('/hunter/profile/mobile', [HunterController::class, 'showProfileMobile']);
    Route::get('/history-komisi-mobile', [HunterController::class, 'historyKomisiMobile']);
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
Route::match(['put', 'post'], '/upload-bukti/{id}', [TransaksiPembelianController::class, 'buktiBayar'])->name('uploadBuktiBayar');


Route::get('/transaksi-pembelian/konfirmasi', [TransaksiPembelianController::class, 'findKonfirmasi']);
Route::post('/transaksi-pembelian/konfirmasi/{id}', [TransaksiPembelianController::class, 'konfirmasi']);
Route::post('/transaksi-pembelian/gagal/{id}', [TransaksiPembelianController::class, 'gagalKonfirmasi']);

                            Route::post('/update-fcm-token', [UserDataController::class, 'updateFcmToken']);





//////////////////////////////////////////////////////MOBILE
Route::get('/pegawai/showKurir/{id}', [PegawaiController::class, 'showKurir']);




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
