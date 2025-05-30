<?php

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PenitipPegawaiController;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\PenitipController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/penitip', [PenitipPegawaiController::class, 'index']);

    Route::get('/penitip/search', [PenitipPegawaiController::class, 'search']);
    Route::post('/penitip/create', [PenitipPegawaiController::class, 'store']);
    Route::get('/penitip/{id}', [PenitipPegawaiController::class, 'show']);
    Route::put('/penitip/{id}', [PenitipPegawaiController::class, 'update']);
    Route::delete('/penitip/{id}', [PenitipPegawaiController::class, 'destroy']);
});

// ORGANISASI
Route::post('/organisasi/register', [OrganisasiController::class, 'register']);

//PEMBELI
Route::post('/pembeli/register', [PembeliController::class, 'register']);

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
