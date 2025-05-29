<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganisasiController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');



Route::middleware('auth:api')->group(function () {

});

// ORGANISASI
Route::post('/organisasi/register', [OrganisasiController::class, 'register']);
