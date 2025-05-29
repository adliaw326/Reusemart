<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardOwnerController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\TransaksiPenitipanController;

Route::get('/', function () {
    return view('login.login');
})->name('login');

Route::get('/login', function () {
    return view('login.login');
})->name('login');

Route::get('/show', function () {
    return view('produk.show');
})->name('show');

Route::get('/kelola_penitip', function () {
    return view('penitip.kelola_penitip');
})->name('kelolaPenitip');

Route::get('/create_penitip', function () {
    return view('penitip.create_penitip');
})->name('createPenitip');

Route::get('/tentang-kami', function () { return view('general.tentang_kami');});



// Route to display the home page
// Route to get product by ID
Route::get('/produk/{kode_produk}', [ProdukController::class, 'show']); // Product detail page
// Route to store a new product
Route::post('/produk', [ProdukController::class, 'store']);
// Route to update product details
Route::put('/produk/{id}', [ProdukController::class, 'update']);
// Route to delete a product
Route::delete('/produk/{id}', [ProdukController::class, 'destroy']);

Route::get('/admin/dashboard', [DashboardController::class, 'index']);

Route::get('/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');
Route::post('/pegawai/store', [PegawaiController::class, 'store'])->name('pegawai.store');
Route::put('pegawai/update/{id}', [PegawaiController::class, 'update']);
Route::delete('pegawai/delete/{id}', [PegawaiController::class, 'destroy']);
Route::match(['get', 'put'], 'pegawai/update/{id}', [PegawaiController::class, 'update']);
Route::post('/pegawai/store', [PegawaiController::class, 'store'])->name('pegawai.store');
Route::get('/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');

Route::get('/owner/history_donasi', [DashboardOwnerController::class, 'showHistory']);

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