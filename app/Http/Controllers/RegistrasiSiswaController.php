<?php

namespace App\Http\Controllers;

use App\Models\RegistrasiSiswa;
use App\Models\Siswa;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RegistrasiSiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSiswa = $user->role === 'siswa';

        $rombels = collect();

        if ($isSiswa) {
            $data = DB::table('registrasi_peserta_didik')
                ->join('peserta_didik', 'registrasi_peserta_didik.peserta_didik_id', '=', 'peserta_didik.id')
                ->join('akun_siswa', 'peserta_didik.id', '=', 'akun_siswa.peserta_didik_id')
                ->where('akun_siswa.email', $user->email)
                ->select(
                    'registrasi_peserta_didik.id as registrasi_id',
                    'peserta_didik.id as siswa_id',
                    'peserta_didik.nama_lengkap',
                    'registrasi_peserta_didik.jenis_pendaftaran',
                    'registrasi_peserta_didik.tanggal_masuk',
                    'registrasi_peserta_didik.sekolah_asal',
                    'registrasi_peserta_didik.no_peserta_un',
                    'registrasi_peserta_didik.no_seri_ijazah',
                    'registrasi_peserta_didik.no_skhun'
                )
                ->orderBy('peserta_didik.nama_lengkap', 'asc')
                ->paginate(12);

            return view('registrasi-siswa.index', compact('data', 'isSiswa'));
        } else {
            $data = DB::table('peserta_didik')
                ->leftJoin('registrasi_peserta_didik', 'peserta_didik.id', '=', 'registrasi_peserta_didik.peserta_didik_id')
                ->leftJoin('rombel', 'peserta_didik.rombel_id', '=', 'rombel.id')
                ->select(
                    'peserta_didik.id as siswa_id',
                    'peserta_didik.rombel_id',
                    'peserta_didik.nama_lengkap',
                    'registrasi_peserta_didik.id as registrasi_id',
                    'registrasi_peserta_didik.jenis_pendaftaran',
                    'registrasi_peserta_didik.tanggal_masuk',
                    'registrasi_peserta_didik.sekolah_asal',
                    'peserta_didik.rombel_id'
                )
                ->orderBy('peserta_didik.nama_lengkap', 'asc')
                ->paginate(12);

            $rombels = DB::table('rombel')->orderBy('nama_rombel')->get();

            return view('registrasi-siswa.index', compact('data', 'rombels', 'isSiswa'));
        }
    }

    public function create()
    {
        $siswa = Siswa::all();
        return view('registrasi-siswa.create', compact('siswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'jenis_pendaftaran' => 'required|in:Siswa Baru,Pindahan,Kembali Bersekolah',
            'tanggal_masuk' => 'required|date',
            'sekolah_asal' => 'nullable|string|max:255',
            'no_peserta_un' => 'nullable|string|max:20',
            'no_seri_ijazah' => 'nullable|string|max:50',
            'no_skhun' => 'nullable|string|max:50',
        ]);

        RegistrasiSiswa::create($request->all());
        return redirect()->route('registrasi-siswa.index')->with('success', 'Data registrasi siswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $registrasi = RegistrasiSiswa::find($id);

        if (!$registrasi) {
            $siswa = Siswa::findOrFail($id);
            $existing = RegistrasiSiswa::where('peserta_didik_id', $siswa->id)->first();

            if ($existing) {
                $registrasi = $existing;
            } else {
                $registrasi = new RegistrasiSiswa();
                $registrasi->peserta_didik_id = $siswa->id;
            }
        } else {
            $siswa = Siswa::find($registrasi->peserta_didik_id);
        }

        $semuaSiswa = Siswa::all();

        return view('registrasi-siswa.edit', [
            'data' => $registrasi,
            'siswa' => $semuaSiswa,
        ]);
    }

    public function update(Request $request, RegistrasiSiswa $registrasi_siswa)
    {
        $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'jenis_pendaftaran' => 'required|in:Siswa Baru,Pindahan,Kembali Bersekolah',
            'tanggal_masuk' => 'required|date',
            'sekolah_asal' => 'nullable|string|max:255',
            'no_peserta_un' => 'nullable|string|max:20',
            'no_seri_ijazah' => 'nullable|string|max:50',
            'no_skhun' => 'nullable|string|max:50',
        ]);

        $registrasi_siswa->update($request->all());
        return redirect()->route('registrasi-siswa.index')->with('success', 'Data registrasi siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $registrasi = RegistrasiSiswa::findOrFail($id);
        RegistrasiSiswa::where('peserta_didik_id', $registrasi->peserta_didik_id)->delete();

        return redirect()->route('registrasi-siswa.index')->with('success', 'Data registrasi siswa berhasil dihapus!');
    }
}
