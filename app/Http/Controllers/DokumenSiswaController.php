<?php

namespace App\Http\Controllers;

use App\Models\DokumenSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DokumenSiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        $query = DB::table('peserta_didik')
            ->leftJoin('dokumen_siswa', 'peserta_didik.id', '=', 'dokumen_siswa.peserta_didik_id')
            ->leftJoin('akun_siswa', 'akun_siswa.peserta_didik_id', '=', 'peserta_didik.id')
            ->select(
                'peserta_didik.id as peserta_didik_id',
                'peserta_didik.nama_lengkap',
                'peserta_didik.rombel_id',
                DB::raw('COUNT(dokumen_siswa.id) as jumlah_dokumen')
            )
            ->groupBy('peserta_didik.id', 'peserta_didik.nama_lengkap', 'peserta_didik.rombel_id')
            ->orderBy('peserta_didik.nama_lengkap', 'asc');


        if ($role === 'siswa') {

            $query->where('akun_siswa.email', $user->email);
            $dokumen = $query->paginate(12);

            $siswa = null;
            $dokumenSiswa = null;

            if ($dokumen->first()) {
                $siswa = DB::table('peserta_didik')->find($dokumen->first()->peserta_didik_id);
                $dokumenSiswa = DB::table('dokumen_siswa')->where('peserta_didik_id', $siswa->id)->first();
            }

            $rombels = DB::table('rombel')->orderBy('nama_rombel')->get();

            return view('dokumen-siswa.index', compact(
                'dokumen', 'rombels', 'siswa', 'dokumenSiswa'
            ))->with('isSiswa', true);
        }

        if ($role === 'ptk') {

            $ptk = DB::table('akun_ptk')
                ->join('ptk', 'akun_ptk.ptk_id', '=', 'ptk.id')
                ->where('akun_ptk.email', $user->email)
                ->select('ptk.*')
                ->first();

            $rayonIds = DB::table('rayon')
                ->where('ptk_id', $ptk->id)
                ->pluck('id');

            $query->whereIn('peserta_didik.rayon_id', $rayonIds);

            $dokumen = $query->paginate(12);
            $rombels = DB::table('rombel')->orderBy('nama_rombel')->get();

            return view('dokumen-siswa.index', compact('dokumen', 'rombels'))
                ->with('isPtk', true);
        }

        $dokumen = $query->paginate(12);

        $rombels = DB::table('rombel')
            ->orderBy('nama_rombel')
            ->get();

        return view('dokumen-siswa.index', compact('dokumen', 'rombels'))
            ->with('isAdmin', true);
    }

    public function create(Request $request)
    {
        $pesertaDidikId = $request->query('peserta_didik_id');
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        if ($pesertaDidikId) {
            $siswa = Siswa::findOrFail($pesertaDidikId);
            return view('dokumen-siswa.create', compact('pesertaDidikId', 'siswa', 'prefix'));
        } else {
            $siswa = Siswa::all();
            return view('dokumen-siswa.create', compact('siswa', 'prefix'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'akte_kelahiran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'kartu_keluarga' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ktp_ayah' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ktp_ibu' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ijazah_sd' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ijazah_smp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $fields = ['akte_kelahiran','kartu_keluarga','ktp_ayah','ktp_ibu','ijazah_sd','ijazah_smp'];

        $data = ['peserta_didik_id' => $request->peserta_didik_id];

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('dokumen_siswa', 'public');
            }
        }

        DokumenSiswa::create($data);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'dokumen-siswa.index')
                        ->with('success', 'Dokumen siswa berhasil diunggah.');
    }

    public function show($peserta_didik_id)
    {
        $dokumen = DokumenSiswa::where('peserta_didik_id', $peserta_didik_id)->first();
        $siswa = Siswa::findOrFail($peserta_didik_id);

        return view('dokumen-siswa.show', compact('dokumen', 'siswa'));
    }

    public function edit($peserta_didik_id)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isSiswa = $user->role === 'siswa';

        $dokumen = DokumenSiswa::firstOrNew(['peserta_didik_id' => $peserta_didik_id]);
        $siswa = Siswa::findOrFail($peserta_didik_id);

        return view('dokumen-siswa.edit', [
            'dokumen' => $dokumen,
            'isAdmin' => $isAdmin,
            'isSiswa' => $isSiswa,
            'siswa' => $siswa,
        ]);
    }

    public function update(Request $request, $peserta_didik_id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $request->validate([
            'akte_kelahiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'kartu_keluarga' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ktp_ayah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ktp_ibu' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ijazah_sd' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ijazah_smp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $dokumen = DokumenSiswa::firstOrNew(['peserta_didik_id' => $peserta_didik_id]);

        foreach (['akte_kelahiran','kartu_keluarga','ktp_ayah','ktp_ibu','ijazah_sd','ijazah_smp'] as $field) {
            if ($request->hasFile($field)) {

                if ($dokumen->$field && Storage::disk('public')->exists($dokumen->$field)) {
                    Storage::disk('public')->delete($dokumen->$field);
                }

                $dokumen->$field = $request->file($field)->store('dokumen_siswa', 'public');
            }
        }

        $dokumen->save();

        return redirect()->route($prefix.'dokumen-siswa.index')
            ->with('success', 'Dokumen siswa berhasil diperbarui.');
    }

    public function destroy($peserta_didik_id)
    {
        $dokumen = DokumenSiswa::where('peserta_didik_id', $peserta_didik_id)->first();

        if ($dokumen) {
            foreach (['akte_kelahiran','kartu_keluarga','ktp_ayah','ktp_ibu','ijazah_sd','ijazah_smp'] as $field) {
                if ($dokumen->$field && Storage::disk('public')->exists($dokumen->$field)) {
                    Storage::disk('public')->delete($dokumen->$field);
                }
            }
            $dokumen->delete();
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'dokumen-siswa.index')
            ->with('success', 'Dokumen siswa berhasil dihapus.');
    }
}
