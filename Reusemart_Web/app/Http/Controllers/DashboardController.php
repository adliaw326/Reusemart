<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Produk;
use App\Models\Penitip;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'totalPegawai' => Pegawai::count(),
            'totalProduk' => Produk::count(),
            'totalPenitip' => Penitip::count(),
            'pegawai' => Pegawai::all(),
            'produk' => Produk::all(),
            'penitip' => Penitip::all(),
        ]);
    }
}
