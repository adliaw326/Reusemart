<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penitip;
use App\Models\Alamat;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
        // Validasi input
        $request->validate([
            'email_penitip' => 'required|email|unique:penitip,EMAIL_PENITIP',
            'password_penitip' => 'required',
            'nama_penitip' => 'required',
            'nik' => 'required',
            'no_telp_penitip' => 'required',
            'alamat' => 'required',
        ]);

        DB::beginTransaction();
        try {
            // Simpan penitip
            $penitip = Penitip::create([
                'EMAIL_PENITIP' => $request->email_penitip,
                'PASSWORD_PENITIP' => $request->password_penitip, // Nanti diganti hash
                // 'PASSWORD_PENITIP' => bcrypt($request->password_penitip), // Aktifkan ini nanti
                'NAMA_PENITIP' => $request->nama_penitip,
                'NIK' => $request->nik,
                'NO_TELP_PENITIP' => $request->no_telp_penitip,
                'RATING_RATA_RATA_P' => null,
                'SALDO_PENITIP' => 0,
                'POIN_PENITIP' => 0,
            ]);

            // Simpan alamat default untuk penitip
            Alamat::create([
                'ID_PEMBELI' => null,
                'ID_ORGANISASI' => null,
                'ID_PENITIP' => $penitip->ID_PENITIP,
                'LOKASI' => $request->alamat,
                'STATUS_DEFAULT' => 1,
            ]);

            DB::commit();

            return response()->json(['message' => 'Penitip berhasil ditambahkan.'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal menyimpan penitip: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $penitip = Penitip::with(['alamatDefault' => function ($query) {
            $query->where('STATUS_DEFAULT', 1);
        }])->find($id);

        if (!$penitip) {
            return response()->json(['message' => 'Penitip tidak ditemukan'], 404);
        }

        return response()->json($penitip);
    }

    public function search(Request $request)
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
        $request->validate([
            'email_penitip' => 'required|email|unique:penitip,EMAIL_PENITIP,' . $id . ',ID_PENITIP',
            'nama_penitip' => 'required',
            'nik' => 'required',
            'no_telp_penitip' => 'required',
            'alamat' => 'required',
        ]);

        $penitip = Penitip::find($id);
        if (!$penitip) {
            return response()->json(['message' => 'Penitip tidak ditemukan'], 404);
        }

        $penitip->EMAIL_PENITIP = $request->email_penitip;
        $penitip->NAMA_PENITIP = $request->nama_penitip;
        $penitip->NIK = $request->nik;
        $penitip->NO_TELP_PENITIP = $request->no_telp_penitip;
        $penitip->save();
        // Update alamat default
        $alamat = Alamat::where('ID_PENITIP', $id)->where('STATUS_DEFAULT', 1)->first();
        if ($alamat) {
            $alamat->LOKASI = $request->alamat;
            $alamat->save();
        }

        return response()->json(['message' => 'Data berhasil diperbarui']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $penitip = Penitip::find($id);

        if (!$penitip) {
            return response()->json(['message' => 'Penitip tidak ditemukan'], 404);
        }

        try {
            $penitip->delete();
            return response()->json(['message' => 'Data penitip berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data'], 500);
        }
    }
}
