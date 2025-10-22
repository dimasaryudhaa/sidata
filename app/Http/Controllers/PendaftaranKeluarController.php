<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranKeluar;
use App\Models\Ptk;
use App\Models\OrangTua;
use App\Models\Siswa;
use Illuminate\Http\Request;

class PendaftaranKeluarController extends Controller
{
    public function index()
    {
        $pendaftaranKeluar = PendaftaranKeluar::with(['siswa', 'ptk', 'orangTua'])
            ->orderBy('id', 'desc')
            ->paginate(12); 

        return view('pendaftaran-keluar.index', compact('pendaftaranKeluar'));
    }

    public function create()
    {
        $ptk = Ptk::all();
        $orangTua = OrangTua::select('id', 'nama_ayah')->get();
        $siswa = Siswa::all();
        return view('pendaftaran-keluar.create', compact('ptk', 'orangTua', 'siswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'orang_tua_id' => 'required|exists:orang_tua,id',
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'keluar_karena' => 'required|in:Mutasi,Dikeluarkan,Mengundurkan Diri,Lulus,Wafat,Hilang,Lainnya',
            'tanggal_keluar' => 'required|date',
            'alasan' => 'nullable|string',
        ]);

        PendaftaranKeluar::create($request->all());

        return redirect()->route('pendaftaran-keluar.index')->with('success', 'Data pendaftaran keluar berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $data = PendaftaranKeluar::findOrFail($id);
        $data->delete();

        return redirect()->route('pendaftaran-keluar.index')->with('success', 'Data berhasil dihapus');
    }
}
