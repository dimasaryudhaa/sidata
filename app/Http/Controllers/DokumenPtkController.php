<?php

namespace App\Http\Controllers;

use App\Models\DokumenPtk;
use App\Models\Ptk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DokumenPtkController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = DB::table('ptk')
            ->leftJoin('dokumen_ptk', 'ptk.id', '=', 'dokumen_ptk.ptk_id')
            ->leftJoin('akun_ptk', 'akun_ptk.ptk_id', '=', 'ptk.id')
            ->select(
                'ptk.id as ptk_id',
                'ptk.nama_lengkap',
                DB::raw('COUNT(dokumen_ptk.id) as jumlah_dokumen')
            )
            ->groupBy('ptk.id', 'ptk.nama_lengkap')
            ->orderBy('ptk.nama_lengkap', 'asc');

        if ($user->role === 'ptk') {
            $query->where('akun_ptk.email', $user->email);
        }

        $dokumen = $query->paginate(12);

        return view('dokumen-ptk.index', compact('dokumen'));
    }

    public function create(Request $request)
    {
        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('dokumen-ptk.create', compact('ptkId', 'ptk'));
        } else {
            $ptks = Ptk::all();
            return view('dokumen-ptk.create', compact('ptks'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'akte_kelahiran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'kartu_keluarga' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ijazah_sd' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ijazah_smp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ijazah_sma' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ijazah_s1' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ijazah_s2' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ijazah_s3' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $files = ['akte_kelahiran','kartu_keluarga','ktp','ijazah_sd','ijazah_smp','ijazah_sma','ijazah_s1','ijazah_s2','ijazah_s3'];

        $data = ['ptk_id' => $request->ptk_id];

        foreach ($files as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('dokumen_ptk', 'public');
            }
        }

        DokumenPtk::create($data);

        return redirect()->route('dokumen-ptk.index')->with('success', 'Dokumen PTK berhasil diunggah.');
    }

    public function show($ptk_id)
    {
        $dokumen = DokumenPtk::where('ptk_id', $ptk_id)->first();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('dokumen-ptk.show', compact('dokumen', 'ptk'));
    }

    public function edit($ptk_id)
    {
        $dokumen = DokumenPtk::firstOrNew(['ptk_id' => $ptk_id]);

        return view('dokumen-ptk.edit', compact('dokumen'));
    }

    public function update(Request $request, $ptk_id)
    {
        $request->validate([
            'akte_kelahiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'kartu_keluarga' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ijazah_sd' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ijazah_smp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ijazah_sma' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ijazah_s1' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ijazah_s2' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ijazah_s3' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $dokumen = DokumenPtk::firstOrNew(['ptk_id' => $ptk_id]);

        foreach (['akte_kelahiran','kartu_keluarga','ktp','ijazah_sd','ijazah_smp','ijazah_sma','ijazah_s1','ijazah_s2','ijazah_s3'] as $field) {
            if ($request->hasFile($field)) {
                if ($dokumen->$field && Storage::disk('public')->exists($dokumen->$field)) {
                    Storage::disk('public')->delete($dokumen->$field);
                }
                $dokumen->$field = $request->file($field)->store('dokumen_ptk', 'public');
            }
        }

        $dokumen->save();

        return redirect()->route('dokumen-ptk.index')->with('success', 'Dokumen PTK berhasil diperbarui.');
    }

    public function destroy($ptk_id)
    {
        $dokumen = DokumenPtk::where('ptk_id', $ptk_id)->first();

        if ($dokumen) {
            foreach (['akte_kelahiran','kartu_keluarga','ktp','ijazah_sd','ijazah_smp','ijazah_sma','ijazah_s1','ijazah_s2','ijazah_s3'] as $field) {
                if ($dokumen->$field && Storage::disk('public')->exists($dokumen->$field)) {
                    Storage::disk('public')->delete($dokumen->$field);
                }
            }
            $dokumen->delete();
        }

        return redirect()->route('dokumen-ptk.index')->with('success', 'Dokumen PTK berhasil dihapus.');
    }
}
