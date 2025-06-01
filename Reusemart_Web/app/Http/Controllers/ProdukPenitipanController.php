<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;

class ProdukPenitipanController extends Controller
{
    public function detailByPenitipan(Request $request)
    {
        $penitipan = DB::table('transaksi_penitipan')->where('ID_PENITIPAN', $request->id_penitipan)->first();

        if (!$penitipan) {
            return response()->json([], 404);
        }

        $produk = Produk::where('KODE_PRODUK', $penitipan->KODE_PRODUK)
            ->with('foto') // relasi ke foto_produk
            ->get();

        return response()->json($produk);
        }
}
