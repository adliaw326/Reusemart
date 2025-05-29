<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Pembeli;

class PembeliController extends Controller
{
    
    public function index()
    {
        return response()->json(Pembeli::all());
    }

    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|unique:pembeli,EMAIL_PEMBELI',
        'password' => 'required|string|min:6',
        'nama' => 'required|string|max:255'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422);
    }

    $lastPembeli = Pembeli::orderBy('ID_PEMBELI', 'desc')->first();

    if ($lastPembeli) {
        $lastIdNumber = intval(substr($lastPembeli->ID_PEMBELI, 2));
        $newIdNumber = $lastIdNumber + 1;
    } else {
        $newIdNumber = 1; // Jika belum ada data, mulai dari 1
    }

    $newId = 'PB' . str_pad($newIdNumber, 3, '0', STR_PAD_LEFT);

    $pembeli = Pembeli::create([  
        'ID_PEMBELI' => $newId,
        'EMAIL_PEMBELI' => $request->email,
        'NAMA_PEMBELI' => $request->nama,
        'PASSWORD_PEMBELI' => Hash::make($request->password),
        'POIN_PEMBELI' => 0,
    ]);

    $token = $pembeli->createToken('api-token')->plainTextToken;

    return response()->json([
        'message' => 'Pembeli berhasil ditambahkan',
        'data' => $pembeli,
        'token' => $token
    ]);
}

    public function show($id)
    {
        $pembeli = Pembeli::find($id);
        if (!$pembeli) {
            return response()->json(['message' => 'Pembeli tidak ditemukan'], 404);
        }
        return response()->json($pembeli);
    }

    public function update(Request $request, $id)
    {
        $pembeli = Pembeli::find($id);
        if (!$pembeli) {
            return response()->json(['message' => 'Pembeli tidak ditemukan'], 404);
        }

        $request->validate([
            'EMAIL_PEMBELI' => 'sometimes|email|unique:pembeli,EMAIL_PEMBELI,' . $id . ',ID_PEMBELI',
            'PASSWORD_PEMBELI' => 'sometimes|string|min:6',
            'NAMA_PEMBELI' => 'sometimes|string|max:255',
            'POIN_PEMBELI' => 'sometimes|integer|min:0'
        ]);

        $pembeli->update($request->all());
        return response()->json(['message' => 'Data pembeli berhasil diperbarui', 'data' => $pembeli]);
    }

    public function destroy($id)
    {
        $pembeli = Pembeli::find($id);
        if (!$pembeli) {
            return response()->json(['message' => 'Pembeli tidak ditemukan'], 404);
        }

        $pembeli->delete();
        return response()->json(['message' => 'Pembeli berhasil dihapus']);
    }
}
