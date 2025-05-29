<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pegawai;
use App\Models\Organisasi;
use App\Models\Penitip;
use App\Models\Pembeli;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // ✅ Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $email = $credentials['email'];
        $password = $credentials['password'];

        // 🔍 1. Cek Pegawai
        $pegawai = Pegawai::where('EMAIL_PEGAWAI', $email)->first();
        if ($pegawai && $this->passwordMatches($password, $pegawai->PASSWORD_PEGAWAI)) {
            $roleMap = [
                'RL001' => 'cs',
                'RL002' => 'owner',
                'RL003' => 'hunter',
                'RL004' => 'kurir',
                'RL005' => 'admin',
                'RL006' => 'pegawai_gudang',
            ];
            $role = $roleMap[$pegawai->ID_ROLE] ?? 'unknown';

            $token = $pegawai->createToken('pegawai-token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'role' => $role,
                'user' => $pegawai
            ], 200);
        }

        // 🔍 2. Cek Penitip
        $penitip = Penitip::where('EMAIL_PENITIP', $email)->first();
        if ($penitip && $this->passwordMatches($password, $penitip->PASSWORD_PENITIP)) {
            $token = $penitip->createToken('penitip-token')->plainTextToken;
            return response()->json([
                'token' => $token,
                'role' => 'penitip',
                'user' => $penitip
            ], 200);
        }

        // 🔍 3. Cek Organisasi
        $organisasi = Organisasi::where('EMAIL_ORGANISASI', $email)->first();
        if ($organisasi && $this->passwordMatches($password, $organisasi->PASSWORD_ORGANISASI)) {
            $token = $organisasi->createToken('organisasi-token')->plainTextToken;
            return response()->json([
                'token' => $token,
                'role' => 'organisasi',
                'user' => $organisasi
            ], 200);
        }

        // 🔍 4. Cek Pembeli
        $pembeli = Pembeli::where('EMAIL_PEMBELI', $email)->first();
        if ($pembeli && $this->passwordMatches($password, $pembeli->PASSWORD_PEMBELI)) {
            $token = $pembeli->createToken('pembeli-token')->plainTextToken;
            return response()->json([
                'token' => $token,
                'role' => 'pembeli',
                'user' => $pembeli
            ], 200);
        }

        // ❌ Tidak ditemukan
        return response()->json([
            'message' => 'Email atau password salah'
        ], 401);
    }

    // 🛡️ Fungsi ini bisa pakai hash atau plaintext tergantung kebutuhan
    private function passwordMatches($inputPassword, $storedPassword)
    {
        // Untuk dummy: pakai perbandingan biasa
        // Nanti saat sudah hash, ganti ke Hash::check()
        return $inputPassword === $storedPassword;
        // return Hash::check($inputPassword, $storedPassword);
    }
}
