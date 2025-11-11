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
        $ptks = Ptk::all();
        $data = new KepegawaianPtk();
        return view('kepegawaian-ptk.edit', compact('data', 'ptks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'status_kepegawaian' => 'required|string|in:PNS,PNS Diperbantukan,PNS Depag,GTY/PTY,GTT/PTT Propinsi,GTT/PTT Kab/Kota,Guru Bantu Pusat,Guru Honor Sekolah,Tenaga Honor,CPNS,PPPK,PPNPN,Kontrak Kerja WNA',
            'nip' => 'required|string|max:18',
            'niy_nigk' => 'required|string|max:50',
            'nuptk' => 'required|string|max:50',
            'jenis_ptk' => 'required|string|in:Kepala Sekolah,Guru,Tenaga Kependidikan',
            'sk_pengangkatan' => 'required|string|max:100',
            'tmt_pengangkatan' => 'required|date',
            'lembaga_pengangkat' => 'required|string|in:Pemerintah Pusat,Pemerintah Provinsi,Pemerintah Kab/Kota,Ketua Yayasan,Kepala Sekolah,Komite Sekolah,Lainnya',
            'sk_cpns' => 'required|string|max:100',
            'tmt_pns' => 'required|date',
            'pangkat_golongan' => 'required|string|max:50',
            'sumber_gaji' => 'required|string|in:APBN,APBD Provinsi,APBD Kab/Kota,Yayasan,Sekolah,Lembaga Donor,Lainnya',
            'kartu_pegawai' => 'required|string|max:50',
            'kartu_keluarga' => 'required|string|max:50',
        ]);

        KepegawaianPtk::create($validated);

        return redirect()->route('kepegawaian-ptk.index')
            ->with('success', 'Data kepegawaian PTK berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kepegawaian = KepegawaianPtk::find($id);

        if (!$kepegawaian) {
            $ptk = Ptk::findOrFail($id);

            $existing = KepegawaianPtk::where('ptk_id', $ptk->id)->first();

            if ($existing) {
                $kepegawaian = $existing;
            } else {
                $kepegawaian = new KepegawaianPtk();
                $kepegawaian->ptk_id = $ptk->id;
            }
        }

        $ptks = Ptk::all();

        return view('kepegawaian-ptk.edit', [
            'data' => $kepegawaian,
            'ptks' => $ptks,
        ]);
    }

    public function update(Request $request, $id)
    {
        $kepegawaian = KepegawaianPtk::findOrFail($id);

        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'status_kepegawaian' => 'required|string|in:PNS,PNS Diperbantukan,PNS Depag,GTY/PTY,GTT/PTT Propinsi,GTT/PTT Kab/Kota,Guru Bantu Pusat,Guru Honor Sekolah,Tenaga Honor,CPNS,PPPK,PPNPN,Kontrak Kerja WNA',
            'nip' => 'required|string|max:18',
            'niy_nigk' => 'required|string|max:50',
            'nuptk' => 'required|string|max:50',
            'jenis_ptk' => 'required|string|in:Kepala Sekolah,Guru,Tenaga Kependidikan',
            'sk_pengangkatan' => 'required|string|max:100',
            'tmt_pengangkatan' => 'required|date',
            'lembaga_pengangkat' => 'required|string|in:Pemerintah Pusat,Pemerintah Provinsi,Pemerintah Kab/Kota,Ketua Yayasan,Kepala Sekolah,Komite Sekolah,Lainnya',
            'sk_cpns' => 'required|string|max:100',
            'tmt_pns' => 'required|date',
            'pangkat_golongan' => 'required|string|max:50',
            'sumber_gaji' => 'required|string|in:APBN,APBD Provinsi,APBD Kab/Kota,Yayasan,Sekolah,Lembaga Donor,Lainnya',
            'kartu_pegawai' => 'required|string|max:50',
            'kartu_keluarga' => 'required|string|max:50',
        ]);

        $kepegawaian->update($validated);

        return redirect()->route('kepegawaian-ptk.index')
            ->with('success', 'Data kepegawaian PTK berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kepegawaian = KepegawaianPtk::findOrFail($id);
        KepegawaianPtk::where('ptk_id', $kepegawaian->ptk_id)->delete();

        return redirect()->route('kepegawaian-ptk.index')
            ->with('success', 'Data kepegawaian PTK berhasil dihapus.');
    }
}
