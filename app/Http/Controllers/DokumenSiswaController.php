<?php

namespace App\Http\Controllers;

use App\Models\DokumenSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DokumenSiswaController extends Controller
{
    public function index()
    {
        $dokumen = DB::table('peserta_didik')
            ->leftJoin('dokumen_siswa', 'peserta_didik.id', '=', 'dokumen_siswa.peserta_didik_id')
            ->select(
                'peserta_didik.id as peserta_didik_id',
                'peserta_didik.nama_lengkap',
                'peserta_didik.rombel_id',
                DB::raw('COUNT(dokumen_siswa.id) as jumlah_dokumen')
            )
            ->groupBy('peserta_didik.id', 'peserta_didik.nama_lengkap', 'peserta_didik.rombel_id')
            ->orderBy('peserta_didik.nama_lengkap', 'asc')
            ->paginate(12);

        $rombels = DB::table('rombel')->select('id', 'nama_rombel')->orderBy('nama_rombel')->get();

        return view('dokumen-siswa.index', compact('dokumen', 'rombels'));
    }

    public function create(Request $request)
    {
        $pesertaDidikId = $request->query('peserta_didik_id');

        if ($pesertaDidikId) {
            $siswa = Siswa::findOrFail($pesertaDidikId);
            return view('dokumen-siswa.create', compact('pesertaDidikId', 'siswa'));
        } else {
            $siswa = Siswa::all();
            return view('dokumen-siswa.create', compact('siswa'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'akte_kelahiran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kartu_keluarga' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'ktp_ayah' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'ktp_ibu' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'ijazah_sd' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'ijazah_smp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $files = ['akte_kelahiran','kartu_keluarga','ktp_ayah','ktp_ibu','ijazah_sd','ijazah_smp'];

        $data = ['peserta_didik_id' => $request->peserta_didik_id];

        foreach ($files as $field) {
            $data[$field] = $request->file($field)->store('dokumen_siswa', 'public');
        }

        DokumenSiswa::create($data);

        return redirect()->route('dokumen-siswa.index')->with('success', 'Dokumen siswa berhasil diunggah.');
    }

    public function show($peserta_didik_id)
    {
        $dokumen = DokumenSiswa::where('peserta_didik_id', $peserta_didik_id)->first();
        $siswa = Siswa::findOrFail($peserta_didik_id);

        return view('dokumen-siswa.show', compact('dokumen', 'siswa'));
    }

    public function edit($peserta_didik_id)
    {
        $dokumen = DokumenSiswa::firstOrNew(['peserta_didik_id' => $peserta_didik_id]);

        return view('dokumen-siswa.edit', compact('dokumen'));
    }

    public function update(Request $request, $peserta_didik_id)
    {
        $request->validate([
            'akte_kelahiran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kartu_keluarga' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'ktp_ayah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'ktp_ibu' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'ijazah_sd' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'ijazah_smp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $dokumen = DokumenSiswa::firstOrNew(['peserta_didik_id' => $peserta_didik_id]);

        foreach (['akte_kelahiran', 'kartu_keluarga', 'ktp_ayah', 'ktp_ibu', 'ijazah_sd', 'ijazah_smp'] as $field) {
            if ($request->hasFile($field)) {
                if ($dokumen->$field && Storage::disk('public')->exists($dokumen->$field)) {
                    Storage::disk('public')->delete($dokumen->$field);
                }

                $dokumen->$field = $request->file($field)->store('dokumen_siswa', 'public');
            }
        }

        $dokumen->save();

        return redirect()->route('dokumen-siswa.index')->with('success', 'Dokumen siswa berhasil diperbarui.');
    }


    public function destroy($peserta_didik_id)
    {
        $dokumen = DokumenSiswa::where('peserta_didik_id', $peserta_didik_id)->first();

        if ($dokumen) {
            foreach (['akte_kelahiran', 'kartu_keluarga', 'ktp_ayah', 'ktp_ibu', 'ijazah_sd', 'ijazah_smp'] as $field) {
                if ($dokumen->$field && Storage::disk('public')->exists($dokumen->$field)) {
                    Storage::disk('public')->delete($dokumen->$field);
                }
            }
            $dokumen->delete();
        }

        return redirect()->route('dokumen-siswa.index')->with('success', 'Dokumen siswa berhasil dihapus.');
    }
}
