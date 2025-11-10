<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KesejahteraanPtk;
use Illuminate\Support\Facades\Auth;
use App\Models\Ptk;

class KesejahteraanPtkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';

        if ($isPtk) {
            $kesejahteraan = DB::table('kesejahteraan_ptk')
                ->join('ptk', 'kesejahteraan_ptk.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'kesejahteraan_ptk.id as kesejahteraan_id',
                    'ptk.id as ptk_id',
                    'kesejahteraan_ptk.jenis_kesejahteraan',
                    'kesejahteraan_ptk.nama',
                    'kesejahteraan_ptk.penyelenggara',
                    'kesejahteraan_ptk.dari_tahun',
                    'kesejahteraan_ptk.sampai_tahun',
                    'kesejahteraan_ptk.status'
                )
                ->orderBy('kesejahteraan_ptk.nama', 'asc')
                ->paginate(12);

            return view('kesejahteraan-ptk.index', compact('kesejahteraan', 'isPtk'));
        } else {
            $kesejahteraan = DB::table('ptk')
                ->leftJoin('kesejahteraan_ptk', 'ptk.id', '=', 'kesejahteraan_ptk.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(kesejahteraan_ptk.id) as jumlah_kesejahteraan')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(12);

            return view('kesejahteraan-ptk.index', compact('kesejahteraan', 'isPtk'));
        }
    }

    public function create(Request $request)
    {
        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('kesejahteraan-ptk.create', compact('ptkId', 'ptk'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('kesejahteraan-ptk.create', compact('ptks'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_kesejahteraan' => 'required|string|max:100',
            'nama' => 'required|string|max:150',
            'penyelenggara' => 'nullable|string|max:150',
            'dari_tahun' => 'nullable|integer|min:1900|max:2100',
            'sampai_tahun' => 'nullable|integer|min:1900|max:2100',
            'status' => 'required|in:Masih Menerima,Tidak Menerima',
        ]);

        KesejahteraanPtk::create($validated);

        return redirect()->route('kesejahteraan-ptk.index')
            ->with('success', 'Data kesejahteraan PTK berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $kesejahteraan = KesejahteraanPtk::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('kesejahteraan-ptk.show', compact('kesejahteraan', 'ptk'));
    }

    public function edit(KesejahteraanPtk $kesejahteraanPtk)
    {
        $ptks = Ptk::orderBy('nama_lengkap')->get();

        return view('kesejahteraan-ptk.edit', compact('kesejahteraanPtk', 'ptks'));
    }

    public function update(Request $request, KesejahteraanPtk $kesejahteraanPtk)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_kesejahteraan' => 'required|string|max:100',
            'nama' => 'required|string|max:150',
            'penyelenggara' => 'nullable|string|max:150',
            'dari_tahun' => 'nullable|integer|min:1900|max:2100',
            'sampai_tahun' => 'nullable|integer|min:1900|max:2100',
            'status' => 'required|in:Masih Menerima,Tidak Menerima',
        ]);

        $kesejahteraanPtk->update($validated);

        return redirect()->route('kesejahteraan-ptk.index')
            ->with('success', 'Data kesejahteraan PTK berhasil diperbarui.');
    }

    public function destroy(KesejahteraanPtk $kesejahteraanPtk)
    {
        $kesejahteraanPtk->delete();

        return redirect()->route('kesejahteraan-ptk.index')
            ->with('success', 'Data kesejahteraan PTK berhasil dihapus.');
    }
}
