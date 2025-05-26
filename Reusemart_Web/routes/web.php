<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;

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

Route::get('/tentang-kami', function () { return view('general.tentang_kami');});