<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardOwnerController;
use App\Http\Controllers\PegawaiController;

Route::get('/tentang-kami', function () { return view('general.tentang_kami');});
// Route to display the home page
Route::get('/', [ProdukController::class, 'index']); // Loads the home page
// Route to get product by ID
Route::get('/produk/{kode_produk}', [ProdukController::class, 'show']); // Product detail page
// Route to store a new product
Route::post('/produk', [ProdukController::class, 'store']); 
// Route to update product details
Route::put('/produk/{id}', [ProdukController::class, 'update']); 
// Route to delete a product
Route::delete('/produk/{id}', [ProdukController::class, 'destroy']); 



Route::get('/admin/dashboard', [DashboardAdminController::class, 'index']);
Route::get('/owner/dashboard', [DashboardOwnerController::class, 'index']);

Route::get('/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');
Route::post('/pegawai/store', [PegawaiController::class, 'store'])->name('pegawai.store');
Route::put('pegawai/update/{id}', [PegawaiController::class, 'update']);
Route::match(['get', 'put'], 'pegawai/update/{id}', [PegawaiController::class, 'update']);
Route::delete('pegawai/delete/{id}', [PegawaiController::class, 'destroy']);

Route::get('/owner/history_donasi', [DashboardOwnerController::class, 'showHistory']);