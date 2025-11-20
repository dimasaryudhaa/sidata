<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatKarir;
use App\Models\Ptk;

class RiwayatKarirController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        if ($isPtk) {
            $riwayatKarir = DB::table('riwayat_karir')
                ->join('ptk', 'riwayat_karir.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'riwayat_karir.id as riwayat_karir_id',
                    'ptk.id as ptk_id',
                    'riwayat_karir.jenjang_pendidikan',
                    'riwayat_karir.jenis_lembaga',
                    'riwayat_karir.status_kepegawaian',
                    'riwayat_karir.jenis_ptk',
                    'riwayat_karir.lembaga_pengangkat',
                    'riwayat_karir.no_sk_kerja',
                    'riwayat_karir.tgl_sk_kerja',
                    'riwayat_karir.tmt_kerja',
                    'riwayat_karir.tst_kerja',
                    'riwayat_karir.tempat_kerja',
                    'riwayat_karir.ttd_sk_kerja'
                )
                ->orderBy('riwayat_karir.tgl_sk_kerja', 'desc')
                ->paginate(12);

        } else {
            $riwayatKarir = DB::table('ptk')
                ->leftJoin('riwayat_karir', 'ptk.id', '=', 'riwayat_karir.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(riwayat_karir.id) as jumlah_riwayat_karir')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap')
                ->paginate(12);
        }

        return view('riwayat-karir.index', compact('riwayatKarir', 'isPtk', 'isAdmin', 'prefix'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('riwayat-karir.create', compact('ptkId', 'ptk', 'prefix'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('riwayat-karir.create', compact('ptks', 'prefix'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenjang_pendidikan' => 'required|string|max:100',
            'jenis_lembaga' => 'required|string|max:100',
            'status_kepegawaian' => 'required|string|max:50',
            'jenis_ptk' => 'required|string|max:100',
            'lembaga_pengangkat' => 'required|string|max:150',
            'no_sk_kerja' => 'required|string|max:50',
            'tgl_sk_kerja' => 'required|date',
            'tmt_kerja' => 'required|date',
            'tst_kerja' => 'nullable|date',
            'tempat_kerja' => 'required|string|max:100',
            'ttd_sk_kerja' => 'required|string|max:100',
        ]);

        RiwayatKarir::create($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'riwayat-karir.index')
            ->with('success', 'Data riwayat karir berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $riwayatKarir = RiwayatKarir::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('riwayat-karir.show', compact('riwayatKarir', 'ptk', 'prefix'));
    }

    public function edit(RiwayatKarir $riwayatKarir)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        $ptks = Ptk::orderBy('nama_lengkap')->get();

        return view('riwayat-karir.edit', compact(
            'riwayatKarir',
            'ptks',
            'isAdmin',
            'isPtk',
            'prefix'
        ));
    }

    public function update(Request $request, RiwayatKarir $riwayatKarir)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenjang_pendidikan' => 'required|string|max:100',
            'jenis_lembaga' => 'required|string|max:100',
            'status_kepegawaian' => 'required|string|max:50',
            'jenis_ptk' => 'required|string|max:100',
            'lembaga_pengangkat' => 'required|string|max:150',
            'no_sk_kerja' => 'required|string|max:50',
            'tgl_sk_kerja' => 'required|date',
            'tmt_kerja' => 'required|date',
            'tst_kerja' => 'nullable|date',
            'tempat_kerja' => 'required|string|max:100',
            'ttd_sk_kerja' => 'required|string|max:100',
        ]);

        $riwayatKarir->update($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'riwayat-karir.index')
            ->with('success', 'Data riwayat karir berhasil diperbarui.');
    }

    public function destroy(RiwayatKarir $riwayatKarir)
    {
        $riwayatKarir->delete();

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'riwayat-karir.index')
            ->with('success', 'Data riwayat karir berhasil dihapus.');
    }
}
