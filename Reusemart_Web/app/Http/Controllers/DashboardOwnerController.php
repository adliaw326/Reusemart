<?php

namespace App\Http\Controllers;

use App\Models\RequestDonasi;
use App\Models\Produk;

class DashboardOwnerController extends Controller
{
    public function index()
    {
        // Fetch all requests with 'pending' status
        $requests = RequestDonasi::with(['produk', 'organisasi'])
                                 ->where('STATUS_REQUEST', 'pending')
                                 ->get();

        // Fetch all products
        $allProducts = Produk::all();

        return view('owner.dashboard', compact('requests', 'allProducts'));
    }

    public function showHistory()
    {
        // Fetch requests with status 'diterima' or 'ditolak'
        $requests = RequestDonasi::with(['produk', 'organisasi'])
                                 ->whereIn('STATUS_REQUEST', ['diterima', 'ditolak'])
                                 ->get();

        // Fetch all products
        $allProducts = Produk::all();

        return view('owner.history_donasi', compact('requests', 'allProducts'));
    }
}