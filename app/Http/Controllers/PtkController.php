<?php

namespace App\Http\Controllers;

use App\Models\Ptk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PtkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';

        if ($isPtk) {
            $ptk = DB::table('akun_ptk')
                ->join('ptk', 'akun_ptk.ptk_id', '=', 'ptk.id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'ptk.id',
                    'ptk.nama_lengkap',
                    'ptk.nik',
                    'ptk.jenis_kelamin',
                    'ptk.tempat_lahir',
                    'ptk.tanggal_lahir',
                    'ptk.nama_ibu_kandung',
                    'ptk.agama',
                    'ptk.npwp',
                    'ptk.nama_wajib_pajak',
                    'ptk.kewarganegaraan',
                    'ptk.negara_asal'
                )
                ->paginate(1);

            return view('ptk.index', compact('ptk', 'isPtk'));
        } else {
            $ptk = DB::table('ptk')
                ->select(
                    'ptk.id',
                    'ptk.nama_lengkap',
                    'ptk.nik',
                    'ptk.jenis_kelamin',
                    'ptk.tempat_lahir',
                    'ptk.tanggal_lahir'
                )
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(15);

            return view('ptk.index', compact('ptk', 'isPtk'));
        }
    }

    public function create()
    {
        return view('ptk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:ptk,nik',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'nama_ibu_kandung' => 'required|string|max:255',
        ]);

        Ptk::create($request->all());

        return redirect()->route('ptk.index')->with('success', 'Data PTK berhasil ditambahkan!');
    }

    public function show(Ptk $ptk)
    {
        return view('ptk.show', compact('ptk'));
    }

    public function edit(Ptk $ptk)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';

        return view('ptk.edit', [
            'ptk' => $ptk,
            'isAdmin' => $isAdmin,
            'isPtk' => $isPtk,
        ]);
    }

    public function update(Request $request, Ptk $ptk)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:ptk,nik,' . $ptk->id,
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'nama_ibu_kandung' => 'required|string|max:255',
        ]);

        $ptk->update($request->all());

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'ptk.index')
            ->with('success', 'Data PTK berhasil diperbarui!');
    }

    public function destroy(Ptk $ptk)
    {
        $ptk->delete();
        return redirect()->route('ptk.index')->with('success', 'Data PTK berhasil dihapus!');
    }
}
