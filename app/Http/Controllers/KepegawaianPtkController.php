<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\KepegawaianPtk;
use App\Models\Ptk;

class KepegawaianPtkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $data = collect();

        if ($isPtk) {
            $data = DB::table('akun_ptk')
                ->join('ptk', 'akun_ptk.ptk_id', '=', 'ptk.id')
                ->leftJoin('kepegawaian', 'ptk.id', '=', 'kepegawaian.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    'kepegawaian.id as kepegawaian_id',
                    'kepegawaian.status_kepegawaian',
                    'kepegawaian.nip',
                    'kepegawaian.niy_nigk',
                    'kepegawaian.nuptk',
                    'kepegawaian.jenis_ptk',
                    'kepegawaian.sk_pengangkatan',
                    'kepegawaian.tmt_pengangkatan',
                    'kepegawaian.lembaga_pengangkat',
                    'kepegawaian.sk_cpns',
                    'kepegawaian.tmt_pns',
                    'kepegawaian.pangkat_golongan',
                    'kepegawaian.sumber_gaji',
                    'kepegawaian.kartu_pegawai',
                    'kepegawaian.kartu_keluarga'
                )
                ->paginate(1);

            return view('kepegawaian-ptk.index', compact('data', 'isPtk'));

        } else {
            $data = DB::table('ptk')
                ->leftJoin('kepegawaian', 'kepegawaian.ptk_id', '=', 'ptk.id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    'kepegawaian.id as kepegawaian_id',
                    'kepegawaian.status_kepegawaian',
                    'kepegawaian.nip',
                    'kepegawaian.niy_nigk',
                    'kepegawaian.nuptk',
                    'kepegawaian.jenis_ptk',
                    'kepegawaian.sk_pengangkatan',
                    'kepegawaian.tmt_pengangkatan',
                    'kepegawaian.lembaga_pengangkat',
                    'kepegawaian.sk_cpns',
                    'kepegawaian.tmt_pns',
                    'kepegawaian.pangkat_golongan',
                    'kepegawaian.sumber_gaji',
                    'kepegawaian.kartu_pegawai',
                    'kepegawaian.kartu_keluarga'
                )
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(12);

            return view('kepegawaian-ptk.index', compact('data', 'isPtk'));
        }
    }


    public function create()
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptks = Ptk::all();
        $data = new KepegawaianPtk();

        return view('kepegawaian-ptk.edit', compact('data', 'ptks', 'prefix'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'status_kepegawaian' => 'required|string',
            'nip' => 'nullable|string|max:18',
            'niy_nigk' => 'nullable|string|max:50',
            'nuptk' => 'nullable|string|max:50',
            'jenis_ptk' => 'required|string',
            'sk_pengangkatan' => 'nullable|string|max:100',
            'tmt_pengangkatan' => 'nullable|date',
            'lembaga_pengangkat' => 'nullable|string',
            'sk_cpns' => 'nullable|string|max:100',
            'tmt_pns' => 'nullable|date',
            'pangkat_golongan' => 'nullable|string|max:50',
            'sumber_gaji' => 'nullable|string',
            'kartu_pegawai' => 'nullable|string|max:50',
            'kartu_keluarga' => 'nullable|string|max:50',
        ]);

        KepegawaianPtk::create($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'kepegawaian-ptk.index')
            ->with('success', 'Data kepegawaian PTK berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';

        $kepegawaian = KepegawaianPtk::find($id);

        if (!$kepegawaian) {
            // jika id bukan id kepegawaian → berarti id PTK
            $ptk = Ptk::findOrFail($id);

            $existing = KepegawaianPtk::where('ptk_id', $ptk->id)->first();

            if ($existing) {
                $kepegawaian = $existing;
            } else {
                $kepegawaian = new KepegawaianPtk();
                $kepegawaian->ptk_id = $ptk->id;
            }
        } else {
            $ptk = Ptk::find($kepegawaian->ptk_id);
        }

        $ptks = Ptk::all();

        return view('kepegawaian-ptk.edit', [
            'data' => $kepegawaian,
            'ptks' => $ptks,
            'isAdmin' => $isAdmin,
            'isPtk' => $isPtk,
            'ptk' => $ptk,
        ]);
    }


    public function update(Request $request, KepegawaianPtk $kepegawaian_ptk)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'status_kepegawaian' => 'required|string',
            'nip' => 'nullable|string|max:18',
            'niy_nigk' => 'nullable|string|max:50',
            'nuptk' => 'nullable|string|max:50',
            'jenis_ptk' => 'required|string',
            'sk_pengangkatan' => 'nullable|string|max:100',
            'tmt_pengangkatan' => 'nullable|date',
            'lembaga_pengangkat' => 'nullable|string',
            'sk_cpns' => 'nullable|string|max:100',
            'tmt_pns' => 'nullable|date',
            'pangkat_golongan' => 'nullable|string|max:50',
            'sumber_gaji' => 'nullable|string',
            'kartu_pegawai' => 'nullable|string|max:50',
            'kartu_keluarga' => 'nullable|string|max:50',
        ]);

        $kepegawaian_ptk->update($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'kepegawaian-ptk.index')
            ->with('success', 'Data kepegawaian PTK berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $kepegawaian = KepegawaianPtk::findOrFail($id);

        KepegawaianPtk::where('ptk_id', $kepegawaian->ptk_id)->delete();

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'kepegawaian-ptk.index')
            ->with('success', 'Data kepegawaian PTK berhasil dihapus.');
    }
}
