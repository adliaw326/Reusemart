<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Penitip;
use App\Models\Pembeli;
use App\Models\Organisasi;

class UserDataController extends Controller
{
    public function getUserData(Request $request)
    {
        $role = $request->input('role');
        $userId = $request->input('userId');

        switch($role) {
            case 'cs':
            case 'owner':
            case 'hunter':
            case 'kurir':
            case 'admin':
            case 'pegawai_gudang':
                $data = Pegawai::select('ID_ROLE', 'NAMA_PEGAWAI', 'EMAIL_PEGAWAI', 'TANGGAL_LAHIR_PEGAWAI')
                    ->where('ID_PEGAWAI', $userId) // sesuaikan nama kolom PK
                    ->first();
                break;

            case 'penitip':
                $data = Penitip::select(
                    'EMAIL_PENITIP',
                    'NAMA_PENITIP',
                    'NIK',
                    'NO_TELP_PENITIP',
                    'RATING_RATA_RATA_P',
                    'SALDO_PENITIP',
                    'POIN_PENITIP'
                )->where('ID_PENITIP', $userId)->first();
                break;

            case 'pembeli':
                $data = Pembeli::select(
                    'EMAIL_PEMBELI',
                    'NAMA_PEMBELI',
                    'POIN_PEMBELI'
                )->where('ID_PEMBELI', $userId)->first();
                break;

            case 'organisasi':
                $data = Organisasi::select('NAMA_ORGANISASI', 'EMAIL_ORGANISASI')
                    ->where('ID_ORGANISASI', $userId)->first();
                break;

            default:
                return response()->json(['error' => 'Role tidak valid'], 400);
        }

        if (!$data) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($data);
    }
}
