<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Tunjangan;
use App\Models\Ptk;
use App\Models\Semester;

class TunjanganController extends Controller
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
            $tunjangan = DB::table('tunjangan')
                ->join('ptk', 'tunjangan.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'tunjangan.id as tunjangan_id',
                    'tunjangan.ptk_id',
                    'tunjangan.jenis_tunjangan',
                    'tunjangan.nama_tunjangan',
                    'tunjangan.instansi',
                    'tunjangan.sk_tunjangan',
                    'tunjangan.tgl_sk_tunjangan',
                    'tunjangan.semester_id',
                    'tunjangan.sumber_dana',
                    'tunjangan.dari_tahun',
                    'tunjangan.sampai_tahun',
                    'tunjangan.nominal',
                    'tunjangan.status'
                )
                ->orderBy('tunjangan.nama_tunjangan', 'asc')
                ->paginate(12);
        } else {
            $tunjangan = DB::table('ptk')
                ->leftJoin('tunjangan', 'ptk.id', '=', 'tunjangan.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(tunjangan.id) as jumlah_tunjangan')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(12);
        }

        return view('tunjangan.index', compact('tunjangan', 'isPtk', 'isAdmin', 'ptkId'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptkId = $request->query('ptk_id');

        $semesters = Semester::orderBy('nama_semester')->get();

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('tunjangan.create', compact('ptkId', 'ptk', 'semesters', 'prefix'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('tunjangan.create', compact('ptks', 'semesters', 'prefix'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_tunjangan' => 'required|string|max:100',
            'nama_tunjangan' => 'required|string|max:150',
            'instansi' => 'nullable|string|max:150',
            'sk_tunjangan' => 'nullable|string|max:100',
            'tgl_sk_tunjangan' => 'nullable|date',
            'semester_id' => 'required|exists:semester,id',
            'sumber_dana' => 'nullable|string|max:100',
            'dari_tahun' => 'nullable|integer|min:1900|max:2100',
            'sampai_tahun' => 'nullable|integer|min:1900|max:2100',
            'nominal' => 'nullable|numeric|min:0',
            'status' => 'required|in:Masih Menerima,Tidak Menerima',
        ]);

        Tunjangan::create($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'tunjangan.index')
                        ->with('success', 'Data tunjangan berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {

        $tunjangan = Tunjangan::where('ptk_id', $ptk_id)->with('semester')->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('tunjangan.show', compact('tunjangan', 'ptk'));
    }


    public function edit(Tunjangan $tunjangan)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';

        $ptks = Ptk::orderBy('nama_lengkap')->get();
        $semesters = Semester::orderBy('nama_semester')->get();

        $ptk = Ptk::find($tunjangan->ptk_id);

        return view('tunjangan.edit', [
            'tunjangan' => $tunjangan,
            'ptks' => $ptks,
            'semesters' => $semesters,
            'isAdmin' => $isAdmin,
            'isPtk' => $isPtk,
            'ptk' => $ptk,
        ]);
    }

    public function update(Request $request, Tunjangan $tunjangan)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_tunjangan' => 'required|string|max:100',
            'nama_tunjangan' => 'required|string|max:150',
            'instansi' => 'nullable|string|max:150',
            'sk_tunjangan' => 'nullable|string|max:100',
            'tgl_sk_tunjangan' => 'nullable|date',
            'semester_id' => 'required|exists:semester,id',
            'sumber_dana' => 'nullable|string|max:100',
            'dari_tahun' => 'nullable|integer|min:1900|max:2100',
            'sampai_tahun' => 'nullable|integer|min:1900|max:2100',
            'nominal' => 'nullable|numeric|min:0',
            'status' => 'required|in:Masih Menerima,Tidak Menerima',
        ]);

        $tunjangan->update($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'tunjangan.index')
            ->with('success', 'Data tunjangan berhasil diperbarui.');
    }

    public function destroy(Tunjangan $tunjangan)
    {
        $tunjangan->delete();

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'tunjangan.index')
            ->with('success', 'Data tunjangan berhasil dihapus.');
    }

}
