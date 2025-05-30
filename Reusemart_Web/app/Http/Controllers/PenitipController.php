<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penitip;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PenitipController extends Controller
{

    public function profile(request $r)
    {
        $penitip = auth()->user();
        // Jika tidak ada penitip yang login, kembalikan error
        if (!$penitip) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // Kembalikan data pembeli dalam JSON
        return response()->json([
            'message' => 'Data pembeli berhasil didapatkan',
            'data' => $penitip
        ]);
    }
    
    public function index()
    {
        return Penitip::all();
    }
  
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_penitip' => 'required|unique:penitip,ID_PENITIP',
            'email_penitip' => 'required|email|unique:penitip,EMAIL_PENITIP',
            'password_penitip' => 'required|min:6',
            'nama_penitip' => 'required',
            'nik' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(' ', $validator->errors()->all())
            ], 422);
        }

        $penitip = Penitip::create([
            'ID_PENITIP' => $request->id_penitip,
            'EMAIL_PENITIP' => $request->email_penitip,
            'PASSWORD_PENITIP' => bcrypt($request->password_penitip),
            'NAMA_PENITIP' => $request->nama_penitip,
            'NIK' => $request->nik
        ]);

        return response()->json(['success' => true, 'data' => $penitip]);
    }

    public function destroy($id)
    {
        $penitip = Penitip::where('ID_PENITIP', $id)->first();
        if (!$penitip) {
            return response()->json(['success' => false, 'message' => 'Penitip tidak ditemukan'], 404);
        }

        $penitip->delete();

        return response()->json(['success' => true, 'message' => 'Penitip berhasil dihapus']);
    }

    public function update(Request $request, $id)
    {
        $penitip = Penitip::find($id);
        if (!$penitip) {
            return response()->json(['success' => false, 'message' => 'Penitip tidak ditemukan.'], 404);
        }

        $request->validate([
            'email_penitip' => 'required|email',
            'nama_penitip' => 'required|string|max:100',
        ]);

        $penitip->EMAIL_PENITIP = $request->email_penitip;
        $penitip->NAMA_PENITIP = $request->nama_penitip;
        $penitip->save();

        return response()->json(['success' => true, 'message' => 'Penitip berhasil diupdate.']);
    }

    public function show($id)
    {
        $penitip = Penitip::find($id);

        if (!$penitip) {
            return response()->json(['message' => 'Penitip tidak ditemukan'], 404);
        }

        return response()->json($penitip);
    }

    public function history_produk(Request $request)
    {
        $penitip = auth()->user();

        if (!$penitip) {
        $penitip = Penitip::find(1); // default jika belum login (opsional, sesuaikan)
        }

        // if (!$penitip) {
        //     abort(403, 'Unauthorized');
        // }

        // Join tabel produk dan transaksi_penitipan berdasarkan KODE_PRODUK dan filter ID_PENITIP
        $produkList = DB::table('produk')
            ->join('transaksi_penitipan', 'produk.KODE_PRODUK', '=', 'transaksi_penitipan.KODE_PRODUK')
            ->where('transaksi_penitipan.ID_PENITIP', $penitip->ID_PENITIP)
            ->select('produk.*', 'transaksi_penitipan.STATUS_PENITIPAN as STATUS')
            ->get();

        return view('penitip.history_penitip', [
            'produk' => $produkList,
            'penitip' => $penitip,
        ]);
    }
}
