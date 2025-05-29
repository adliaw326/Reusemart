<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penitip;
use Illuminate\Support\Str; // ⬅ Tambahkan ini

class PenitipPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penitips = Penitip::with('alamatDefault')->get();
        $penitips = $penitips->map(function ($p) {
            return [
                'ID_PENITIP' => 'T' . $p->ID_PENITIP, // prefix "T"
                'EMAIL_PENITIP' => $p->EMAIL_PENITIP,
                'NAMA_PENITIP' => $p->NAMA_PENITIP,
                'NIK' => $p->NIK,
                'NO_TELP_PENITIP' => $p->NO_TELP_PENITIP,
                'RATING_RATA_RATA_P' => $p->RATING_RATA_RATA_P,
                'SALDO_PENITIP' => $p->SALDO_PENITIP,
                'POIN_PENITIP' => $p->POIN_PENITIP,
                'alamat_default' => $p->alamatDefault,
            ];
        });
        return response()->json($penitips);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $query = $request->q;

        // Jika diawali dengan T, ambil angka setelah T
        if (Str::startsWith($query, 'T')) {
            $numericId = (int) Str::after($query, 'T');
            $penitip = Penitip::with('alamatDefault')
                ->where('ID_PENITIP', $numericId)
                ->get();
        } else {
            // Pencarian normal berdasarkan nama/email misalnya
            $penitip = Penitip::with('alamatDefault')
                ->where('NAMA_PENITIP', 'like', '%' . $query . '%')
                ->orWhere('EMAIL_PENITIP', 'like', '%' . $query . '%')
                ->orWhere('NIK', 'like', '%' . $query . '%')
                ->orWhere('NO_TELP_PENITIP', 'like', '%' . $query . '%')
                ->orWhere('RATING_RATA_RATA_P', 'like', '%' . $query . '%')
                ->orWhere('SALDO_PENITIP', 'like', '%' . $query . '%')
                ->orWhere('POIN_PENITIP', 'like', '%' . $query . '%')
                ->orWhereHas('alamatDefault', function($q) use ($query) {
                    $q->where('LOKASI', 'like', '%' . $query . '%');
                })
                ->get();
        }

        $penitip = $penitip->map(function ($p) {
            return [
                'ID_PENITIP' => 'T' . $p->ID_PENITIP,
                'EMAIL_PENITIP' => $p->EMAIL_PENITIP,
                'NAMA_PENITIP' => $p->NAMA_PENITIP,
                'NIK' => $p->NIK,
                'NO_TELP_PENITIP' => $p->NO_TELP_PENITIP,
                'RATING_RATA_RATA_P' => $p->RATING_RATA_RATA_P,
                'SALDO_PENITIP' => $p->SALDO_PENITIP,
                'POIN_PENITIP' => $p->POIN_PENITIP,
                'alamat_default' => $p->alamatDefault,
            ];
        });

        return response()->json($penitip);
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
