<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alamat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AlamatController extends Controller
{
    /**
     * Tampilkan semua data alamat.
     */
    public function getAlamatByPemilik($id)
    {
        $alamat = DB::table('alamat')
            ->where('ID_PEMBELI', $id)
            ->orWhere('ID_ORGANISASI', $id)
            ->get();

        return response()->json($alamat);
    }
    public function index()
    {
        $data = Alamat::all();
        return view('alamat.index', compact('data'));
    }

    public function find($ID_PEMBELI)
    {
        $alamat = Alamat::where('ID_PEMBELI', $ID_PEMBELI)
            // ->orWhere('ID_ORGANISASI', $ID_PEMBELI)
            ->get();

        if ($alamat->isEmpty()) {
            return response()->json(['message' => 'Alamat tidak ditemukan'], 404);
        }

        return response()->json([
            'alamat' => $alamat
        ]);
    }

    /**
     * Tampilkan detail alamat berdasarkan ID.
     */
    public function show($id)
    {
        $alamat = Alamat::findOrFail($id);
        return view('alamat.show', compact('alamat'));
    }

    public function setDefault($id)
    {
        $alamat = DB::table('alamat')->where('ID_ALAMAT', $id)->first();

        if (!$alamat) {
            return response()->json(['message' => 'Alamat tidak ditemukan'], 404);
        }

        $field = $alamat->ID_PEMBELI ? 'ID_PEMBELI' : 'ID_ORGANISASI';
        $owner = $alamat->$field;

        DB::table('alamat')
            ->where($field, $owner)
            ->update(['STATUS_DEFAULT' => 0]);

        DB::table('alamat')
            ->where('ID_ALAMAT', $id)
            ->update(['STATUS_DEFAULT' => 1]);

        return response()->json(['message' => 'Alamat berhasil dijadikan default']);
    }
    /**
     * Simpan data alamat baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ID_PEMILIK' => 'required|string|max:10',
            'LOKASI' => 'required|string|max:255',
        ]);

        // Deteksi apakah ID_PEMILIK milik pembeli atau organisasi
        if (Str::startsWith($request->ID_PEMILIK, 'PB')) {
            $pemilik = "PEMBELI";
        } else {
            $pemilik = "ORGANISASI";
        }

        // Buat ID alamat baru
        $lastAlamat = Alamat::orderBy('ID_ALAMAT', 'desc')->first();

        if ($lastAlamat) {
            $lastIdNumber = intval(substr($lastAlamat->ID_ALAMAT, 2));
            $newIdNumber = $lastIdNumber + 1;
        } else {
            $newIdNumber = 1; // Mulai dari 1 jika belum ada data
        }

        $newId = 'AT' . str_pad($newIdNumber, 3, '0', STR_PAD_LEFT);
        $field = 'ID_' . $pemilik;
        try {
            // Simpan alamat baru
            $alamat = new Alamat();
            $alamat->ID_ALAMAT = $newId; // Simpan ID hasil generate
            $alamat->{$field} = $request->ID_PEMILIK;
            $alamat->LOKASI = $request->LOKASI;
            $alamat->STATUS_DEFAULT = 0; // default non-utama
            $alamat->save();

            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil ditambahkan',
                'data' => $alamat
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan alamat',
                'error' => $e->getMessage()
            ], 500);
        }
        $validated = $request->validate([
            'ID_PEMBELI'     => 'nullable|required_without:ID_ORGANISASI|string',
            'ID_ORGANISASI'  => 'nullable|required_without:ID_PEMBELI|string',
            'ID_ALAMAT'      => 'required|string|unique:alamat,ID_ALAMAT',
            'LOKASI'         => 'required|string',
            'STATUS_DEFAULT' => 'required|integer|in:0,1',
        ]);

        Alamat::create($validated);

        return redirect()->back()->with('success', 'Alamat berhasil ditambahkan.');
    }

    /**
     * Perbarui data alamat berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        $alamat = Alamat::findOrFail($id);
        // Validasi input
        $validated = $request->validate([
            'LOKASI' => 'required|string|max:255',
        ]);

        // Update hanya lokasi
        $alamat->LOKASI = $validated['LOKASI'];
        $alamat->save();

        // Kembalikan respon ke user
        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil diperbarui.',
            'data'    => $alamat
        ]);
        $validated = $request->validate([
            'ID_PEMBELI'     => 'nullable|required_without:ID_ORGANISASI|string',
            'ID_ORGANISASI'  => 'nullable|required_without:ID_PEMBELI|string',
            'LOKASI'         => 'required|string',
            'STATUS_DEFAULT' => 'required|integer|in:0,1',
        ]);

        $alamat->update($validated);

        return redirect()->back()->with('success', 'Alamat berhasil diperbarui.');
    }

    /**
     * Hapus data alamat berdasarkan ID.
     */
    // public function delete($id)
    //     {
    //         try {
    //         $alamat = Alamat::findOrFail($id); // Cari alamat berdasarkan ID, gagal jika tidak ditemukan
    //         $alamat->delete(); // Hapus data alamat

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Alamat berhasil dihapus.'
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Gagal menghapus alamat.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    //     public function getPemilikAttribute()
    // {
    //     return $this->pembeli ?? $this->organisasi;
    // }


    public function alamatDefaultPembeli($id_pembeli)
    {
        $alamat = \App\Models\Alamat::where('ID_PEMBELI', $id_pembeli)
            ->where('STATUS_DEFAULT', 1)
            ->first();

        if ($alamat) {
            return response()->json($alamat->LOKASI);
        } else {
            return response()->json('');
        }
    }
}
