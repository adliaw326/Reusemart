<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\RolePegawai;

class PegawaiController extends Controller
{
    // Tampilkan semua pegawai
    public function index()
    {
        return response()->json(Pegawai::all());
    }

public function create()
{
    // Ambil pegawai terakhir berdasarkan ID_PEGAWAI
    $lastPegawai = Pegawai::orderBy('ID_PEGAWAI', 'desc')->first();

    if ($lastPegawai) {
        $lastId = $lastPegawai->ID_PEGAWAI;
        $num = (int)$lastId; // Ambil angka langsung tanpa "P"
        $num++; // Increment angka ID
        $newId = $num; // Gunakan angka saja, tanpa format "P"
    } else {
        $newId = 1; // Mulai dari 1 jika belum ada pegawai
    }

    // Ambil semua role untuk dropdown
    $roles = RolePegawai::all();

    return view('pegawai.create_pegawai', compact('newId', 'roles'));
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
            // 'TANGGAL_LAHIR' => 'required|date',
        ]);

        $pegawai = Pegawai::create($request->all());
         return redirect('/admin/dashboard')->with('success', 'Pegawai berhasil ditambahkan!');
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

    public function update(Request $request, $id)
    {
        if ($request->isMethod('get')) {
            $pegawai = Pegawai::find($id);
            if (!$pegawai) {
                return redirect('admin/dashboard')->with('error', 'Pegawai tidak ditemukan');
            }
            $roles = RolePegawai::all();
            return view('pegawai.update_pegawai', compact('pegawai', 'roles'));
        }

        // Handle PUT request (update data)
        $pegawai = Pegawai::find($id);
        if (!$pegawai) {
            return redirect('admin/dashboard')->with('error', 'Pegawai tidak ditemukan');
        }

        $request->validate([
            'NAMA_PEGAWAI' => 'required|string|max:255',
            'EMAIL_PEGAWAI' => 'required|email|unique:pegawai,EMAIL_PEGAWAI,' . $id . ',ID_PEGAWAI',
            'PASSWORD_PEGAWAI' => 'nullable|string|min:6',
            'ID_ROLE' => 'required',
        ]);

        $pegawai->NAMA_PEGAWAI = $request->NAMA_PEGAWAI;
        $pegawai->EMAIL_PEGAWAI = $request->EMAIL_PEGAWAI;
        $pegawai->ID_ROLE = $request->ID_ROLE;

    if ($request->filled('PASSWORD_PEGAWAI')) {
        // Simpan password apa adanya, tanpa hash
        $pegawai->PASSWORD_PEGAWAI = $request->PASSWORD_PEGAWAI;
    }

        $pegawai->save();

        return redirect('admin/dashboard')->with('success', 'Pegawai berhasil diperbarui');
    }

    // Hapus pegawai
    public function destroy($id)
    {
        $pegawai = Pegawai::find($id);
        if (!$pegawai) {
            return redirect()->back()->with('error', 'Pegawai tidak ditemukan');
        }
        $pegawai->delete();
        return redirect()->back()->with('success', 'Pegawai berhasil dihapus');
    }

    public function getByRole($id_role)
    {
        $pegawai = Pegawai::where('ID_ROLE', $id_role)->select('ID_PEGAWAI', 'NAMA_PEGAWAI')->get();
        return response()->json($pegawai);
    }
}
