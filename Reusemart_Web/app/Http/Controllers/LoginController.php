<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pegawai;
use App\Models\Organisasi;
use App\Models\Penitip;
use App\Models\Pembeli;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


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
                'user' => $pegawai,
                'userId' => $pegawai->ID_PEGAWAI
            ], 200);
        }

        // 🔍 2. Cek Penitip
        $penitip = Penitip::where('EMAIL_PENITIP', $email)->first();
        if ($penitip && $this->passwordMatches($password, $penitip->PASSWORD_PENITIP)) {
            $token = $penitip->createToken('penitip-token')->plainTextToken;
            return response()->json([
                'token' => $token,
                'role' => 'penitip',
                'user' => $penitip,
                'userId' => $penitip->ID_PENITIP
            ], 200);
        }

        // 🔍 3. Cek Organisasi
        $organisasi = Organisasi::where('EMAIL_ORGANISASI', $email)->first();
        if ($organisasi && $this->passwordMatches($password, $organisasi->PASSWORD_ORGANISASI)) {
            $token = $organisasi->createToken('organisasi-token')->plainTextToken;
            return response()->json([
                'token' => $token,
                'role' => 'organisasi',
                'user' => $organisasi,
                'userId' => $organisasi->ID_ORGANISASI
            ], 200);
        }

        // 🔍 4. Cek Pembeli
        $pembeli = Pembeli::where('EMAIL_PEMBELI', $email)->first();
        if ($pembeli && $this->passwordMatches($password, $pembeli->PASSWORD_PEMBELI)) {
            $token = $pembeli->createToken('pembeli-token')->plainTextToken;
            return response()->json([
                'token' => $token,
                'role' => 'pembeli',
                'user' => $pembeli,
                'userId' => $pembeli->ID_PEMBELI
            ], 200);
        }

        // ❌ Tidak ditemukan
        return response()->json([
            'message' => 'Email atau password salah'
        ], 401);
    }

    // 🛡 Fungsi ini bisa pakai hash atau plaintext tergantung kebutuhan
    private function passwordMatches($inputPassword, $storedPassword)
    {
        // Untuk dummy: pakai perbandingan biasa
        // Nanti saat sudah hash, ganti ke Hash::check()
        return $inputPassword === $storedPassword;
        // return Hash::check($inputPassword, $storedPassword);
    }

    public function resetPassword(Request $request)
    {
        $email = $request->input('email');
        $user = Pegawai::where('EMAIL_PEGAWAI', $email)->first();
        if ($user) {
            // Reset ke default (misal: tanggal lahir dalam format dd/mm/yyyy)
            $user->PASSWORD_PEGAWAI = bcrypt(Carbon::parse($user->tanggal_lahir)->format('dmY'));
            $user->save();
            return back()->with('status', 'Password berhasil direset ke default: tanggal lahir (ddmmyyyy).');
        }

        $user = Penitip::where('EMAIL_PENITIP', $email)->first();

        if (!$user) {
            $user = Organisasi::where('EMAIL_ORGANISASI', $email)->first();
        }

        if (!$user) {
            $user = Pembeli::where('EMAIL_PEMBELI', $email)->first();
        }
        
        if (!$user) {
            return back()->with('error', 'Email tidak ditemukan.');
        }                

        Mail::to($email)->send(new ResetPasswordMail($user));
            return back()->with('status', 'Instruksi reset password telah dikirim ke email Anda.');
    }

    public function showResetForm(Request $request)
    {
        // Tangkap email atau token dari query string
        $email = $request->query('email'); // sesuai yang kamu kirim di email

        return view('login.forgot_password_customer', ['email' => $email]);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $email = $request->input('email');
        $password = $request->input('password');

        // Cari user dari semua tabel yang ada
        $user = Penitip::where('EMAIL_PENITIP', $email)->first()
                ?? Organisasi::where('EMAIL_ORGANISASI', $email)->first()
                ?? Pembeli::where('EMAIL_PEMBELI', $email)->first();

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        // Update password (gunakan kolom password yang sesuai)
        // Misal untuk Pegawai kolomnya PASSWORD_PEGAWAI, sesuaikan ya
        if ($user instanceof Penitip) {
            $user->PASSWORD_PENITIP = Hash::make($password);
        } elseif ($user instanceof Organisasi) {
            $user->PASSWORD_ORGANISASI = Hash::make($password);
        } elseif ($user instanceof Pembeli) {
            $user->PASSWORD_PEMBELI = Hash::make($password);
        }
        $user->save();

        return redirect('/login')->with('status', 'Password berhasil diubah. Silakan login dengan password baru.');
    }

}