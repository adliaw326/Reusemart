<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RolePegawai;

class RolePegawaiController extends Controller
{
    public function index()
    {
        return response()->json(RolePegawai::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'ID_ROLE' => 'required|unique:role_pegawai,ID_ROLE',
            'NAMA_ROLE' => 'required|string|max:255',
        ]);

        $role = RolePegawai::create($request->all());
        return response()->json(['message' => 'Role pegawai berhasil ditambahkan', 'data' => $role]);
    }

    public function show($id)
    {
        $role = RolePegawai::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role pegawai tidak ditemukan'], 404);
        }
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $role = RolePegawai::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role pegawai tidak ditemukan'], 404);
        }

        $request->validate([
            'NAMA_ROLE' => 'sometimes|required|string|max:255',
        ]);

        $role->update($request->all());
        return response()->json(['message' => 'Role pegawai berhasil diperbarui', 'data' => $role]);
    }

    public function destroy($id)
    {
        $role = RolePegawai::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role pegawai tidak ditemukan'], 404);
        }

        $role->delete();
        return response()->json(['message' => 'Role pegawai berhasil dihapus']);
    }
}
