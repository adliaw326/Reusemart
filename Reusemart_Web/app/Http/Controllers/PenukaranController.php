<?php

namespace App\Http\Controllers;

use App\Models\Penukaran;
use App\Models\Pembeli;
use App\Models\Merchandise;
use Illuminate\Http\Request;

class PenukaranController extends Controller
{
    public function index()
    {
        // Ambil semua penukaran beserta relasi dengan pembeli dan merchandise
        $penukarans = Penukaran::with(['pembeli', 'merchandise'])->get();
        return view('penukaran.show_penukaran', compact('penukarans'));
    }

    public function filterSudahDiambil()
    {
        // Ambil penukaran yang sudah diambil (tanggal ambil tidak null)
        $penukarans = Penukaran::whereNotNull('TANGGAL_AMBIL_MERC')
                                ->with(['pembeli', 'merchandise'])
                                ->get();
        return view('penukaran.show_penukaran', compact('penukarans'));
    }

    public function filterBelumDiambil()
    {
        // Ambil penukaran yang belum diambil (tanggal ambil null)
        $penukarans = Penukaran::whereNull('TANGGAL_AMBIL_MERC')
                                ->with(['pembeli', 'merchandise'])
                                ->get();
        return view('penukaran.show_penukaran', compact('penukarans'));
    }

    public function show($id)
    {
        $penukaran = Penukaran::with(['pembeli', 'merchandise'])->find($id);
        if (!$penukaran) {
            return redirect()->route('penukaran.index')->with('error', 'Penukaran tidak ditemukan');
        }
        return view('penukaran.show', compact('penukaran'));
    }


    // Membuat penukaran baru
    public function store(Request $request)
    {
        $request->validate([
            'ID_PENUKARAN' => 'required|unique:penukaran,ID_PENUKARAN',
            'ID_PEMBELI' => 'required|exists:pembeli,ID_PEMBELI',
            'ID_MERCHANDISE' => 'required|exists:merchandise,ID_MERCHANDISE',
            'JUMLAH_PENUKARAN' => 'required|numeric',
            'JUMLAH_HARGA_POIN' => 'required|numeric',
            'TANGGAL_CLAIM_PENU' => 'required|date',
            'TANGGAL_AMBIL_MERC' => 'required|date',
        ]);

        $penukaran = Penukaran::create($request->all());

        return response()->json([
            'message' => 'Penukaran created successfully',
            'data' => $penukaran
        ], 201);
    }

    public function edit($id)
    {
        // Cari penukaran berdasarkan ID
        $penukaran = Penukaran::find($id);
        if (!$penukaran) {
            return redirect()->route('penukaran.show')->with('error', 'Penukaran tidak ditemukan');
        }

        // Ambil data pembeli dan merchandise untuk dropdown atau select input
        $pembelis = Pembeli::all();
        $merchandises = Merchandise::all();

        // Tampilkan form edit dengan data penukaran
        return view('penukaran.update_penukaran', compact('penukaran', 'pembelis', 'merchandises'));
    }


    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'ID_PEMBELI' => 'required|exists:pembeli,ID_PEMBELI',
            'ID_MERCHANDISE' => 'required|exists:merchandise,ID_MERCHANDISE',
            'JUMLAH_PENUKARAN' => 'required|numeric',
            'JUMLAH_HARGA_POIN' => 'required|numeric',
            'TANGGAL_CLAIM_PENU' => 'required|date',
            'TANGGAL_AMBIL_MERC' => 'required|date',
        ]);

        // Cari penukaran berdasarkan ID
        $penukaran = Penukaran::find($id);
        if (!$penukaran) {
            return redirect()->route('penukaran.show')->with('error', 'Penukaran tidak ditemukan');
        }

        // Update data penukaran
        $penukaran->update($request->all());

        return redirect()->route('penukaran.show')->with('success', 'Penukaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        // Cari penukaran berdasarkan ID
        $penukaran = Penukaran::find($id);
        if (!$penukaran) {
            return redirect()->route('penukaran.show')->with('error', 'Penukaran tidak ditemukan');
        }

        // Hapus penukaran
        $penukaran->delete();

        return redirect()->route('penukaran.show')->with('success', 'Penukaran berhasil dihapus');
    }
}
