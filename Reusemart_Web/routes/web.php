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

// Route to display the home page
// Route to get product by ID
// Route::get('/produk/{kode_produk}', [ProdukController::class, 'show']); // Product detail page
// Route to store a new product
// Route::post('/produk', [ProdukController::class, 'store']);
// Route to update product details
// Route::put('/produk/{id}', [ProdukController::class, 'update']);
// // Route to delete a product
// Route::delete('/produk/{id}', [ProdukController::class, 'destroy']);

// Route::get('/admin/dashboard', [DashboardController::class, 'index']);

// Route::get('/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');
// Route::post('/pegawai/store', [PegawaiController::class, 'store'])->name('pegawai.store');
// Route::put('pegawai/update/{id}', [PegawaiController::class, 'update']);
Route::delete('pegawai/delete/{id}', [PegawaiController::class, 'destroy']);
Route::match(['get', 'put'], 'pegawai/update/{id}', [PegawaiController::class, 'update']);
Route::post('/pegawai/store', [PegawaiController::class, 'store'])->name('pegawai.store');
Route::get('/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');

Route::get('/owner/history_donasi', [DashboardOwnerController::class, 'showHistory']);
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
    $penitip = auth()->user();    
    if(!$penitip) {
        $penitip = Penitip::find(1); // Default to first penitip if no user is authenticated
    }
    return view('penitip.profile_penitip', ['penitip' => $penitip]);
});
// histori punya penitip
Route::get('/penitip/histori', [PenitipController::class, 'history_produk'])->name('historiPenitip');


//diskusi
Route::post('/diskusi/store', [DiskusiProdukController::class, 'store'])->name('diskusi.store');

//keranjang
    Route::post('/keranjang/store/{KODE_PRODUK}', [KeranjangController::class, 'store'])->name('keranjang.store');
    Route::delete('/keranjang/delete/{KODE_PRODUK}', [KeranjangController::class, 'delete'])->name('keranjang.delete');
    Route::get('/keranjang/check/{kodeProduk}', [KeranjangController::class, 'checkInKeranjang']);
//KEVIN===============================================================================================================
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