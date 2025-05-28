<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPenitipan;
use App\Models\Produk;
use App\Models\KategoriProduk;

class TransaksiPenitipanController extends Controller
{
    public function index()
    {
        // Get all ongoing transactions
        $ongoingTransactions = TransaksiPenitipan::where('STATUS_PENITIPAN', 'sedang berlangsung')->get();

        // Get the product codes associated with ongoing transactions
        $ongoingProductCodes = $ongoingTransactions->pluck('KODE_PRODUK');

        // Retrieve the products related to the ongoing transactions
        $produk = Produk::whereIn('KODE_PRODUK', $ongoingProductCodes)->get();

        // Retrieve categories or any other data you need
        $kategori = KategoriProduk::all();

        return view('general.home', compact('produk', 'kategori'));
    }
}
