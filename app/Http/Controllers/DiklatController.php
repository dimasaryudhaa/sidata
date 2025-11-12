<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Diklat;
use App\Models\Ptk;

class DiklatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';

        if ($isPtk) {
            $diklats = DB::table('diklat')
                ->join('ptk', 'diklat.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'diklat.id as diklat_id',
                    'ptk.id as ptk_id',
                    'diklat.jenis_diklat',
                    'diklat.nama_diklat',
                    'diklat.no_sertifikat',
                    'diklat.penyelenggara',
                    'diklat.tahun',
                    'diklat.peran',
                    'diklat.tingkat',
                )
                ->orderBy('diklat.tahun', 'desc')
                ->paginate(12);

            return view('diklat.index', compact('diklats', 'isPtk'));

        } else {
            $diklats = DB::table('ptk')
                ->leftJoin('diklat', 'ptk.id', '=', 'diklat.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(diklat.id) as jumlah_diklat')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(12);

            return view('diklat.index', compact('diklats', 'isPtk'));
        }
    }

    public function show($ptk_id)
    {
        $ptk = Ptk::findOrFail($ptk_id);
        $diklat = Diklat::where('ptk_id', $ptk_id)->get();
        return view('diklat.show', compact('ptk', 'diklat'));
    }

    public function create(Request $request)
    {
        $ptks = Ptk::all();
        $ptkId = $request->query('ptk_id');
        $ptk = $ptkId ? Ptk::findOrFail($ptkId) : null;

        return view('diklat.create', compact('ptks', 'ptk', 'ptkId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_diklat' => 'required|string|max:150',
            'nama_diklat' => 'required|string|max:150',
            'no_sertifikat' => 'nullable|string|max:50',
            'penyelenggara' => 'required|string|max:150',
            'tahun' => 'required|digits:4|integer',
            'peran' => 'nullable|in:Pemateri,Narasumber,Peserta,Panitia',
            'tingkat' => 'nullable|string|max:255',
        ]);

        Diklat::create($validated);

        return redirect()->route('diklat.index')->with('success', 'Data diklat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $diklat = Diklat::findOrFail($id);
        $ptk = Ptk::findOrFail($diklat->ptk_id);
        return view('diklat.edit', compact('diklat', 'ptk'));
    }

    public function update(Request $request, Diklat $diklat)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_diklat' => 'required|string|max:150',
            'nama_diklat' => 'required|string|max:150',
            'no_sertifikat' => 'nullable|string|max:50',
            'penyelenggara' => 'required|string|max:150',
            'tahun' => 'required|digits:4|integer',
            'peran' => 'nullable|in:Pemateri,Narasumber,Peserta,Panitia',
            'tingkat' => 'nullable|string|max:255',
        ]);

        $diklat->update($validated);

        return redirect()->route('diklat.index')->with('success', 'Data diklat berhasil diperbarui.');
    }

    public function destroy(Diklat $diklat)
    {
        $diklat->delete();
        return redirect()->route('diklat.index')->with('success', 'Data diklat berhasil dihapus.');
    }
}
