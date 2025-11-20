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
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

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

        } else {
            $diklats = DB::table('ptk')
                ->leftJoin('diklat', 'ptk.id', '=', 'diklat.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(diklat.id) as jumlah_diklat')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap')
                ->paginate(12);
        }

        return view('diklat.index', compact('diklats', 'isPtk', 'isAdmin', 'prefix'));
    }

    public function show($ptk_id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptk = Ptk::findOrFail($ptk_id);
        $diklat = Diklat::where('ptk_id', $ptk_id)->get();

        return view('diklat.show', compact('ptk', 'diklat', 'prefix'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('diklat.create', compact('ptkId', 'ptk', 'prefix'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('diklat.create', compact('ptks', 'prefix'));
        }
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

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'diklat.index')
            ->with('success', 'Data diklat berhasil ditambahkan.');
    }

    public function edit(Diklat $diklat)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        $ptks = Ptk::orderBy('nama_lengkap')->get();
        $ptk = Ptk::findOrFail($diklat->ptk_id);

        return view('diklat.edit', compact(
            'diklat',
            'ptks',
            'ptk',
            'isAdmin',
            'isPtk',
            'prefix'
        ));
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

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'diklat.index')
            ->with('success', 'Data diklat berhasil diperbarui.');
    }

    public function destroy(Diklat $diklat)
    {
        $diklat->delete();

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'diklat.index')
            ->with('success', 'Data diklat berhasil dihapus.');
    }
}
