<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisasi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class OrganisasiController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required|email|unique:organisasi,email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        $lastOrganisasi = Organisasi::orderBy('ID_ORGANISASI', 'desc')->first();
        if ($lastOrganisasi) {
            // Ambil angka terakhir dari ID_ORGANISASI, misal dari ORG03 ambil 03
            $lastIdNumber = intval(substr($lastOrganisasi->ID_ORGANISASI, 3));
            $newIdNumber = $lastIdNumber + 1;
        } else {
            $newIdNumber = 1; // Jika belum ada data, mulai dari 1
        }

        $newId = 'ORG' . str_pad($newIdNumber, 2, '0', STR_PAD_LEFT);
        $organisasi = Organisasi::create([  
            'ID_ORGANISASI' => $newId,
            'NAMA_ORGANISASI' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // $token = $organisasi->createToken('api-token')->plainTextToken;

        // return response()->json(['organisasi' => $organisasi, 'token' => $token]);
        return response()->json([
            'message' => 'Organisasi berhasil ditambahkan',
            'data' => $organisasi
            // 'token' => $token
        ]);
    }

    // LOGIN
    public function login(Request $request)
    {
        $organisasi = Organisasi::where('email', $request->email)->first();

        if (!$organisasi || !Hash::check($request->password, $organisasi->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = $organisasi->createToken('api-token')->plainTextToken;

        return response()->json(['organisasi' => $organisasi, 'token' => $token]);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    // CRUD

    public function index()
    {
        return Organisasi::all();
    }

    public function show($id)
    {
        return Organisasi::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $org = Organisasi::findOrFail($id);
        $org->update($request->only(['NAMA_ORGANISASI', 'email']));
        return $org;
    }

    public function destroy($id)
    {
        $org = Organisasi::findOrFail($id);
        $org->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
