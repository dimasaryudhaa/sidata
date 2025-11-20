<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BeasiswaPtk;
use App\Models\Ptk;
use Illuminate\Support\Facades\Auth;

class BeasiswaPtkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        if ($isPtk) {
            $beasiswaPtk = DB::table('beasiswa_ptk')
                ->join('ptk', 'beasiswa_ptk.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'beasiswa_ptk.id as beasiswa_id',
                    'ptk.id as ptk_id',
                    'beasiswa_ptk.jenis_beasiswa',
                    'beasiswa_ptk.keterangan',
                    'beasiswa_ptk.tahun_mulai',
                    'beasiswa_ptk.tahun_akhir',
                    'beasiswa_ptk.masih_menerima'
                )
                ->orderBy('beasiswa_ptk.tahun_mulai', 'desc')
                ->paginate(12);

        } else {
            $beasiswaPtk = DB::table('ptk')
                ->leftJoin('beasiswa_ptk', 'ptk.id', '=', 'beasiswa_ptk.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(beasiswa_ptk.id) as jumlah_beasiswa')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(12);
        }

        return view('beasiswa-ptk.index', compact('beasiswaPtk', 'isPtk', 'isAdmin', 'prefix'));
    }

    public function show($ptk_id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptk = Ptk::findOrFail($ptk_id);
        $beasiswa = BeasiswaPtk::where('ptk_id', $ptk_id)->get();

        return view('beasiswa-ptk.show', compact('ptk', 'beasiswa', 'prefix'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('beasiswa-ptk.create', compact('ptkId', 'ptk', 'prefix'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('beasiswa-ptk.create', compact('ptks', 'prefix'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_beasiswa' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:500',
            'tahun_mulai' => 'required|digits:4|integer',
            'tahun_akhir' => 'required|digits:4|integer',
            'masih_menerima' => 'nullable|boolean',
        ]);

        BeasiswaPtk::create($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'beasiswa-ptk.index')
            ->with('success', 'Data beasiswa PTK berhasil ditambahkan.');
    }

    public function edit(BeasiswaPtk $beasiswaPtk)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        $ptk = Ptk::findOrFail($beasiswaPtk->ptk_id);

        return view('beasiswa-ptk.edit', compact(
            'beasiswaPtk',
            'ptk',
            'isAdmin',
            'isPtk',
            'prefix'
        ));
    }

    public function update(Request $request, BeasiswaPtk $beasiswaPtk)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_beasiswa' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:500',
            'tahun_mulai' => 'required|digits:4|integer',
            'tahun_akhir' => 'required|digits:4|integer',
            'masih_menerima' => 'nullable|boolean',
        ]);

        $beasiswaPtk->update($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'beasiswa-ptk.index')
            ->with('success', 'Data beasiswa PTK berhasil diperbarui.');
    }

    public function destroy(BeasiswaPtk $beasiswaPtk)
    {
        $beasiswaPtk->delete();

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'beasiswa-ptk.index')
            ->with('success', 'Data beasiswa PTK berhasil dihapus.');
    }
}
