<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MerchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $merch = DB::table('merchandise')->get();

        return response()->json([
            'success' => true,
            'data' => $merch
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pembeli' => 'required|string',
            'id_merch' => 'required|string',
            'jumlah_penukaran' => 'required|integer|min:1',
            'jumlah_harga_poin' => 'required|integer|min:1',
        ]);

        $idPembeli = $validated['id_pembeli'];
        $idMerch = $validated['id_merch'];
        $jumlah = $validated['jumlah_penukaran'];
        $totalPoin = $validated['jumlah_harga_poin'];

        // Ambil poin pembeli saat ini
        $pembeli = DB::table('pembeli')->where('ID_PEMBELI', $idPembeli)->first();

        if (!$pembeli) {
            return response()->json(['success' => false, 'message' => 'Pembeli tidak ditemukan'], 404);
        }

        if ($pembeli->POIN_PEMBELI < $totalPoin) {
            return response()->json(['success' => false, 'message' => 'Poin tidak mencukupi'], 400);
        }

        // Cek stok merch
        $merch = DB::table('merchandise')->where('ID_MERCHANDISE', $idMerch)->first();

        if (!$merch || $merch->JUMLAH_MERCH < $jumlah) {
            return response()->json(['success' => false, 'message' => 'Stok merch tidak cukup'], 400);
        }

        try {
            DB::beginTransaction();

            // Kurangi poin pembeli
            DB::table('pembeli')->where('ID_PEMBELI', $idPembeli)->decrement('POIN_PEMBELI', $totalPoin);

            // Kurangi stok merch
            DB::table('merchandise')->where('ID_MERCHANDISE', $idMerch)->decrement('JUMLAH_MERCH', $jumlah);

            // Simpan penukaran
            DB::table('penukaran')->insert([
                'ID_PEMBELI' => $idPembeli,
                'ID_MERCHANDISE' => $idMerch,
                'JUMLAH_PENUKARAN' => $jumlah,
                'JUMLAH_HARGA_POIN' => $totalPoin,
                'TANGGAL_CLAIM_PENU' => Carbon::now(),
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Penukaran berhasil']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
