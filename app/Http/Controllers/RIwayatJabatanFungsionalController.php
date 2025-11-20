<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Ptk;
use App\Models\RiwayatJabatanFungsional;

class RiwayatJabatanFungsionalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        if ($isPtk) {

            $riwayatJabatanFungsional = DB::table('riwayat_jabatan_fungsional')
                ->join('ptk', 'riwayat_jabatan_fungsional.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'riwayat_jabatan_fungsional.id as riwayat_jabfung_id',
                    'ptk.id as ptk_id',
                    'riwayat_jabatan_fungsional.jabatan_fungsional',
                    'riwayat_jabatan_fungsional.sk_jabfung',
                    'riwayat_jabatan_fungsional.tmt_jabatan'
                )
                ->orderBy('riwayat_jabatan_fungsional.tmt_jabatan', 'desc')
                ->paginate(12);

        } else {

            $riwayatJabatanFungsional = DB::table('ptk')
                ->leftJoin('riwayat_jabatan_fungsional', 'ptk.id', '=', 'riwayat_jabatan_fungsional.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(riwayat_jabatan_fungsional.id) as jumlah_riwayat_jabfung')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap')
                ->paginate(12);
        }

        return view('riwayat-jabatan-fungsional.index', compact(
            'riwayatJabatanFungsional',
            'isPtk',
            'isAdmin',
            'prefix'
        ));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('riwayat-jabatan-fungsional.create', compact('ptkId', 'ptk', 'prefix'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('riwayat-jabatan-fungsional.create', compact('ptks', 'prefix'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jabatan_fungsional' => 'required|string|max:100',
            'sk_jabfung' => 'required|string|min:10|max:50',
            'tmt_jabatan' => 'required|date',
        ]);

        RiwayatJabatanFungsional::create($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'riwayat-jabatan-fungsional.index')
            ->with('success', 'Data riwayat jabatan fungsional berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptk = Ptk::findOrFail($ptk_id);
        $riwayatJabatanFungsional = RiwayatJabatanFungsional::where('ptk_id', $ptk_id)->get();

        return view('riwayat-jabatan-fungsional.show', compact(
            'ptk',
            'riwayatJabatanFungsional',
            'prefix'
        ));
    }

    public function edit(RiwayatJabatanFungsional $riwayatJabatanFungsional)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        $ptks = Ptk::orderBy('nama_lengkap')->get();

        return view('riwayat-jabatan-fungsional.edit', compact(
            'riwayatJabatanFungsional',
            'ptks',
            'isAdmin',
            'isPtk',
            'prefix'
        ));
    }

    public function update(Request $request, RiwayatJabatanFungsional $riwayatJabatanFungsional)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jabatan_fungsional' => 'required|string|max:100',
            'sk_jabfung' => 'required|string|min:10|max:50',
            'tmt_jabatan' => 'required|date',
        ]);

        $riwayatJabatanFungsional->update($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'riwayat-jabatan-fungsional.index')
            ->with('success', 'Data riwayat jabatan fungsional berhasil diperbarui.');
    }

    public function destroy(RiwayatJabatanFungsional $riwayatJabatanFungsional)
    {
        $riwayatJabatanFungsional->delete();

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'riwayat-jabatan-fungsional.index')
            ->with('success', 'Data riwayat jabatan fungsional berhasil dihapus.');
    }
}
