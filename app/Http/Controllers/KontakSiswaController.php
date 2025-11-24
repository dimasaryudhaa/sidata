<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KontakSiswa;
use App\Models\Siswa;
use App\Models\Rombel;
use Illuminate\Support\Facades\Auth;

class KontakSiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSiswa = $user->role === 'siswa';
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $rombels = collect();

        if ($isSiswa) {
            $data = DB::table('kontak_peserta_didik')
                ->join('peserta_didik', 'kontak_peserta_didik.peserta_didik_id', '=', 'peserta_didik.id')
                ->join('akun_siswa', 'peserta_didik.id', '=', 'akun_siswa.peserta_didik_id')
                ->where('akun_siswa.email', $user->email)
                ->select('kontak_peserta_didik.*', 'peserta_didik.nama_lengkap')
                ->orderBy('peserta_didik.nama_lengkap')
                ->paginate(12);

            return view('kontak-siswa.index', compact('data', 'isSiswa', 'prefix'));
        } else {
            $data = DB::table('peserta_didik')
                ->leftJoin('kontak_peserta_didik', 'peserta_didik.id', '=', 'kontak_peserta_didik.peserta_didik_id')
                ->leftJoin('rombel', 'peserta_didik.rombel_id', '=', 'rombel.id')
                ->select(
                    'peserta_didik.id as siswa_id',
                    'peserta_didik.nama_lengkap',
                    'peserta_didik.rombel_id',
                    'rombel.nama_rombel',
                    'kontak_peserta_didik.*'
                )
                ->orderBy('peserta_didik.nama_lengkap')
                ->paginate(12);

            $rombels = DB::table('rombel')->orderBy('nama_rombel')->get();

            return view('kontak-siswa.index', compact('data', 'rombels', 'isSiswa', 'prefix'));
        }
    }

    public function create()
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $siswa = Siswa::all();
        return view('kontak-siswa.create', compact('siswa', 'prefix'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:100',
            'alamat_jalan' => 'nullable|string|max:255',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'kelurahan' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:10',
            'tempat_tinggal' => 'nullable|in:Bersama Orang Tua,Kos,Asrama,Lainnya',
            'moda_transportasi' => 'nullable|string|max:255',
            'anak_ke' => 'nullable|integer',
        ]);

        KontakSiswa::create($validated);

        $prefix = Auth::user()->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'kontak-siswa.index')
            ->with('success', 'Data kontak siswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $kontak = KontakSiswa::find($id);

        if (!$kontak) {
            $siswa = Siswa::findOrFail($id);
            $existing = KontakSiswa::where('peserta_didik_id', $siswa->id)->first();

            $kontak = $existing ?? new KontakSiswa(['peserta_didik_id' => $siswa->id]);
        }

        $semuaSiswa = Siswa::all();

        return view('kontak-siswa.edit', [
            'data' => $kontak,
            'siswa' => $semuaSiswa,
            'prefix' => $prefix
        ]);
    }

    public function update(Request $request, $id)
    {
        $kontak = KontakSiswa::findOrFail($id);

        $validated = $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:100',
            'alamat_jalan' => 'nullable|string|max:255',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'kelurahan' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:10',
            'tempat_tinggal' => 'nullable|in:Bersama Orang Tua,Kos,Asrama,Lainnya',
            'moda_transportasi' => 'nullable|string|max:255',
            'anak_ke' => 'nullable|integer',
        ]);

        $kontak->update($validated);

        $prefix = Auth::user()->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'kontak-siswa.index')
            ->with('success', 'Data kontak siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kontak = KontakSiswa::findOrFail($id);
        KontakSiswa::where('peserta_didik_id', $kontak->peserta_didik_id)->delete();

        $prefix = Auth::user()->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'kontak-siswa.index')
            ->with('success', 'Data kontak siswa berhasil dihapus.');
    }
}
