<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    // Tampilkan semua notifikasi
    public function index()
    {
        $data = Notifikasi::with(['pembeli', 'penitip', 'pegawai'])->get();
        return response()->json($data);
    }

    // Simpan notifikasi baru
    public function store(Request $request)
    {
        $request->validate([
            'ISI' => 'required|string',
            'ID_PEMBELI' => 'nullable|exists:pembeli,ID_PEMBELI',
            'ID_PENITIP' => 'nullable|exists:penitip,ID_PENITIP',
            'ID_PEGAWAI' => 'nullable|exists:pegawai,ID_PEGAWAI',
        ]);

        $notifikasi = Notifikasi::create($request->only('ID_PEMBELI', 'ID_PENITIP', 'ID_PEGAWAI', 'ISI'));
        return response()->json($notifikasi, 201);
    }

    // Tampilkan notifikasi spesifik
    public function show($id)
    {
        $notifikasi = Notifikasi::with(['pembeli', 'penitip', 'pegawai'])->findOrFail($id);
        return response()->json($notifikasi);
    }

    // Hapus notifikasi
    public function destroy($id)
    {
        $notif = Notifikasi::findOrFail($id);
        $notif->delete();

        return response()->json(['message' => 'Notifikasi dihapus']);
    }

    public function findPembeli($id){
        $notif = Notifikasi::where('ID_PEMBELI', $id)
            ->orderBy('TANGGAL', 'desc') // urut dari terbaru ke terlama
            ->get();
        return response()->json($notif);
    }

    public function findPenitip($id){
        $notif = Notifikasi::where('ID_PENITIP', $id)
            ->orderBy('TANGGAL', 'desc')
            ->get();
        // if(!$notif){
        //     return response()->json(['message' => ' tidak ditemukan'], 404);
        // }
        return response()->json($notif);
    }

        
}
