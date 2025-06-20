<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Produk;
use App\Models\Komisi;

class HunterController extends Controller
{
    public function showProfileMobile(Request $request)
    {
        // Ambil user yang sedang login
        $user = $request->user();

        // Ambil data profil
        $profile = Pegawai::where('ID_PEGAWAI', $user->ID_PEGAWAI)->first();

        if (!$profile) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Ambil semua produk yang ID_HUNTER-nya sama dengan ID_PEGAWAI login
        $produkIds = Produk::where('ID_HUNTER', $user->ID_PEGAWAI)->pluck('KODE_PRODUK');

        // Total komisi hunter dari semua produk tersebut
        $totalKomisi = Komisi::whereIn('KODE_PRODUK', $produkIds)->sum('KOMISI_HUNTER');

        return response()->json([
            'id_pegawai' => $profile->ID_PEGAWAI,
            'name' => $profile->NAMA_PEGAWAI,
            'email' => $profile->EMAIL_PEGAWAI,
            'lahir' => $profile->TANGGAL_LAHIR_PEGAWAI,
            'total_komisi' => $totalKomisi,
        ]);
    }

    public function historyKomisiMobile(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Ambil produk yang hunter-nya adalah user ini
        $produkList = Produk::where('ID_HUNTER', $user->ID_PEGAWAI)->pluck('KODE_PRODUK');

        if ($produkList->isEmpty()) {
            return response()->json([]);
        }

        // Ambil komisi terkait
        $komisiList = Komisi::whereIn('KODE_PRODUK', $produkList)
            ->select('ID_KOMISI', 'KODE_PRODUK', 'KOMISI_HUNTER')
            ->get();

        // Ambil data produk untuk ditambahkan ke response
        $produkDetails = Produk::whereIn('KODE_PRODUK', $produkList)
            ->get()
            ->keyBy('KODE_PRODUK');

        // Gabungkan data komisi dengan detail produk
        $result = $komisiList->map(function ($item) use ($produkDetails) {
            $produk = $produkDetails[$item->KODE_PRODUK] ?? null;
            return [
                'id_komisi' => $item->ID_KOMISI,
                'kode_produk' => $item->KODE_PRODUK,
                'komisi_hunter' => $item->KOMISI_HUNTER,
                'produk' => $produk ? [
                    'nama_produk' => $produk->NAMA_PRODUK,
                    'kategori' => $produk->KATEGORI,
                    'harga' => $produk->HARGA,
                    'berat' => $produk->BERAT,
                ] : null,
            ];
        });

        return response()->json($result);
    }
}
