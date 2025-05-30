<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $data = [
            'totalPegawai' => Pegawai::count(),
            'pegawai'      => Pegawai::all(),
        ];

        return view('admin.dashboard', $data);
    }
}
