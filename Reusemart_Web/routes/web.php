<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardOwnerController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\TransaksiPenitipanController;

Route::get('/', [TransaksiPenitipanController::class, 'index']);
Route::get('/tentang-kami', function () { return view('general.tentang_kami');});

Route::post('/produk', [ProdukController::class, 'store']); 
Route::put('/produk/{id}', [ProdukController::class, 'update']); 
Route::delete('/produk/{id}', [ProdukController::class, 'destroy']); 
Route::get('/produk/{kode_produk}', [ProdukController::class, 'show']);

Route::get('/admin/dashboard', [DashboardAdminController::class, 'index']);
Route::get('/owner/dashboard', [DashboardOwnerController::class, 'index']);

Route::put('pegawai/update/{id}', [PegawaiController::class, 'update']);
Route::delete('pegawai/delete/{id}', [PegawaiController::class, 'destroy']);
Route::match(['get', 'put'], 'pegawai/update/{id}', [PegawaiController::class, 'update']);
Route::post('/pegawai/store', [PegawaiController::class, 'store'])->name('pegawai.store');
Route::get('/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');

Route::get('/owner/history_donasi', [DashboardOwnerController::class, 'showHistory']);
