<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;

class PegawaiController extends Controller
{
    // Tampilkan semua pegawai
    public function index()
    {
        return response()->json(Pegawai::all());
    }

    // Simpan pegawai baru
    public function store(Request $request)
    {
        $request->validate([
            'ID_PEGAWAI' => 'required|unique:pegawai,ID_PEGAWAI',
            'ID_ROLE' => 'required',
            'NAMA_PEGAWAI' => 'required|string|max:255',
            'EMAIL_PEGAWAI' => 'required|email|unique:pegawai,EMAIL_PEGAWAI',
            'PASSWORD_PEGAWAI' => 'required|string|min:6',
            'TANGGAL_LAHIR' => 'required|date',
        ]);

        $pegawai = Pegawai::create($request->all());
        return response()->json(['message' => 'Pegawai berhasil ditambahkan', 'data' => $pegawai]);
    }

    // Tampilkan satu pegawai
    public function show($id)
    {
        $pegawai = Pegawai::find($id);
        if (!$pegawai) {
            return response()->json(['message' => 'Pegawai tidak ditemukan'], 404);
        }
        return response()->json($pegawai);
    }

    // Perbarui data pegawai
    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::find($id);
        if (!$pegawai) {
            return response()->json(['message' => 'Pegawai tidak ditemukan'], 404);
        }

        $request->validate([
            'NAMA_PEGAWAI' => 'sometimes|required|string|max:255',
            'EMAIL_PEGAWAI' => 'sometimes|required|email|unique:pegawai,EMAIL_PEGAWAI,' . $id . ',ID_PEGAWAI',
            'PASSWORD_PEGAWAI' => 'sometimes|required|string|min:6',
            'TANGGAL_LAHIR' => 'sometimes|required|date',
        ]);

        $pegawai->update($request->all());
        return response()->json(['message' => 'Pegawai berhasil diperbarui', 'data' => $pegawai]);
    }

    // Hapus pegawai
    public function destroy($id)
    {
        $pegawai = Pegawai::find($id);
        if (!$pegawai) {
            return response()->json(['message' => 'Pegawai tidak ditemukan'], 404);
        }
        $pegawai->delete();
        return response()->json(['message' => 'Pegawai berhasil dihapus']);
    }
}
