<?php

namespace App\Http\Controllers;

use App\Models\AnakPtk;
use App\Models\Ptk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AnakPtkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $isAdmin = $user->role === 'admin';

        $ptkId = null;
        if ($isPtk) {
            $ptkId = DB::table('akun_ptk')
                ->where('email', $user->email)
                ->value('ptk_id');
        }

        if ($isPtk) {
            $anakPtk = DB::table('anak')
                ->join('ptk', 'anak.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'anak.id as anak_id',
                    'anak.ptk_id',
                    'anak.nama_anak',
                    'anak.status_anak',
                    'anak.jenjang',
                    'anak.nisn',
                    'anak.jenis_kelamin',
                    'anak.tempat_lahir',
                    'anak.tanggal_lahir',
                    'anak.tahun_masuk'
                )
                ->orderBy('anak.nama_anak', 'asc')
                ->paginate(12);
        } else {
            $anakPtk = DB::table('ptk')
                ->leftJoin('anak', 'ptk.id', '=', 'anak.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(anak.id) as jumlah_anak')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(12);
        }

        return view('anak-ptk.index', compact('anakPtk', 'isPtk', 'isAdmin', 'ptkId'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('anak-ptk.create', compact('ptkId', 'ptk', 'prefix'));
        } else {
            $ptks = Ptk::all();
            return view('anak-ptk.create', compact('ptks', 'prefix'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'nama_anak' => 'required|string|max:255',
            'status_anak' => 'required|string|max:100',
            'jenjang' => 'required|string|max:100',
            'nisn' => 'nullable|digits:10',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'tahun_masuk' => 'required|digits:4',
        ]);

        AnakPtk::create($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'anak-ptk.index')->with('success', 'Data anak PTK berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $anak = AnakPtk::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('anak-ptk.show', compact('anak', 'ptk'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';

        $anak = AnakPtk::find($id);

        if (!$anak) {
            $ptk = Ptk::findOrFail($id);
            $existing = AnakPtk::where('ptk_id', $ptk->id)->first();

            if ($existing) {
                $anak = $existing;
            } else {
                $anak = new AnakPtk();
                $anak->ptk_id = $ptk->id;
            }
        } else {
            $ptk = Ptk::find($anak->ptk_id);
        }

        $ptks = Ptk::all();

        return view('anak-ptk.edit', [
            'anak' => $anak,
            'ptks' => $ptks,
            'isAdmin' => $isAdmin,
            'isPtk' => $isPtk,
            'ptk' => $ptk,
        ]);
    }

    public function update(Request $request, $id)
    {
        $anak = AnakPtk::findOrFail($id);

        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'nama_anak' => 'required|string|max:255',
            'status_anak' => 'required|string|max:100',
            'jenjang' => 'required|string|max:100',
            'nisn' => 'nullable|digits:10',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'tahun_masuk' => 'required|digits:4',
        ]);

        $anak->update($validated);

        $prefix = Auth::user()->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'anak-ptk.index')
            ->with('success', 'Data Anak PTK berhasil diperbarui.');
    }

    public function destroy($id)
    {
        AnakPtk::findOrFail($id)->delete();

        $prefix = Auth::user()->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'anak-ptk.index')
            ->with('success', 'Data Anak PTK berhasil dihapus.');
    }


}
