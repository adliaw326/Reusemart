<?php

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PenitipPegawaiController;
use App\Http\Controllers\OrganisasiController;

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
