<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\Rombel;
use App\Models\Rayon;
use App\Models\Ptk;
use App\Models\BeasiswaSiswa;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'ptk':
                return redirect()->route('ptk.dashboard');
            case 'siswa':
                return redirect()->route('siswa.dashboard');
            default:
                abort(403);
        }
    }

    public function admin()
    {
        return view('home', [
            'role' => 'admin',
            'jumlahSiswa' => Siswa::count(),
            'jumlahJurusan' => Jurusan::count(),
            'jumlahRombel' => Rombel::count(),
            'jumlahRayon' => Rayon::count(),
            'jumlahPtk' => Ptk::count(),
        ]);
    }

    public function ptk()
    {
        $user = Auth::user();
        $ptk = $user->akunPtk?->ptk;

        $dataLengkap = $ptk
            ? (
                $ptk->nama_lengkap &&
                $ptk->nik &&
                $ptk->jenis_kelamin &&
                $ptk->tempat_lahir &&
                $ptk->tanggal_lahir &&
                $ptk->nama_ibu_kandung &&
                $ptk->kode_pos &&
                $ptk->agama &&
                $ptk->npwp &&
                $ptk->nama_wajib_pajak &&
                $ptk->kewarganegaraan &&
                $ptk->negara_asal
            )
            : false;
        $sudahMengisi = $dataLengkap ? 1 : 0;

        $dokumenPtk = $ptk?->dokumenPtk;
        $jumlahDokumenDiunggah = $dokumenPtk
            ? collect([
                $dokumenPtk->akte_kelahiran,
                $dokumenPtk->kartu_keluarga,
                $dokumenPtk->ktp,
                $dokumenPtk->ijazah_sd,
                $dokumenPtk->ijazah_smp,
                $dokumenPtk->ijazah_sma,
                $dokumenPtk->ijazah_s1,
                $dokumenPtk->ijazah_s2,
                $dokumenPtk->ijazah_s3,
            ])->filter()->count()
            : 0;
        $sudahMengunggahDokumen = $jumlahDokumenDiunggah > 0 ? 1 : 0;

        $keluarga = $ptk?->keluarga;
        $sudahMengisiKeluarga = $keluarga && $keluarga->count() > 0 ? 1 : 0;

        $penugasan = $ptk?->penugasan;
        $sudahIsiPenugasan = $penugasan && $penugasan->count() > 0 ? 1 : 0;

        $kepegawaian = $ptk?->kepegawaian;
        $sudahIsiKepegawaian = $kepegawaian ? 1 : 0;

        $jumlahAnak = $ptk?->anak()->count() ?? 0;
        $jumlahTunjangan = $ptk?->tunjangan()->count() ?? 0;
        $jumlahKesejahteraan = $ptk?->kesejahteraan()->count() ?? 0;
        $jumlahTugasTambahan = $ptk?->tugasTambahan()->count() ?? 0;
        $jumlahRiwayatGaji = $ptk?->riwayatGaji()->count() ?? 0;
        $jumlahRiwayatKarir = $ptk?->riwayatKarir()->count() ?? 0;
        $jumlahRiwayatJabatan = $ptk?->riwayatJabatan()->count() ?? 0;
        $jumlahRiwayatKepangkatan = $ptk?->riwayatKepangkatan()->count() ?? 0;
        $jumlahRiwayatJabatanFungsional = $ptk?->riwayatJabatanFungsional()->count() ?? 0;
        $jumlahDiklat = $ptk?->diklat()->count() ?? 0;
        $jumlahNilaiTest = $ptk?->nilaiTest()->count() ?? 0;
        $jumlahPendidikanPtk = $ptk?->pendidikan()->count() ?? 0;
        $jumlahSertifikat = $ptk?->sertifikat()->count() ?? 0;
        $jumlahBeasiswa = $ptk?->beasiswa()->count() ?? 0;
        $jumlahPenghargaan = $ptk?->penghargaan()->count() ?? 0;
        $jumlahKompetensi = $ptk?->kompetensi()->count() ?? 0;

        return view('home', [
            'role' => 'ptk',
            'user' => $user,
            'ptk' => $ptk,
            'sudahMengisi' => $sudahMengisi,
            'dokumenPtk' => $dokumenPtk,
            'sudahMengunggahDokumen' => $sudahMengunggahDokumen,
            'keluarga' => $keluarga,
            'sudahMengisiKeluarga' => $sudahMengisiKeluarga,
            'penugasan' => $penugasan,
            'sudahIsiPenugasan' => $sudahIsiPenugasan,
            'kepegawaian' => $kepegawaian,
            'sudahIsiKepegawaian' => $sudahIsiKepegawaian,
            'jumlahAnak' => $jumlahAnak,
            'jumlahTunjangan' => $jumlahTunjangan,
            'jumlahKesejahteraan' => $jumlahKesejahteraan,
            'jumlahTugasTambahan' => $jumlahTugasTambahan,
            'jumlahRiwayatGaji' => $jumlahRiwayatGaji,
            'jumlahRiwayatKarir' => $jumlahRiwayatKarir,
            'jumlahRiwayatJabatan' => $jumlahRiwayatJabatan,
            'jumlahRiwayatKepangkatan' => $jumlahRiwayatKepangkatan,
            'jumlahRiwayatJabatanFungsional' => $jumlahRiwayatJabatanFungsional,
            'jumlahDiklat' => $jumlahDiklat,
            'jumlahNilaiTest' => $jumlahNilaiTest,
            'jumlahPendidikanPtk' => $jumlahPendidikanPtk,
            'jumlahSertifikat' => $jumlahSertifikat,
            'jumlahBeasiswa' => $jumlahBeasiswa,
            'jumlahPenghargaan' => $jumlahPenghargaan,
            'jumlahKompetensi' => $jumlahKompetensi,
        ]);
    }

    public function siswa()
    {
        $user = Auth::user();
        $siswa = $user->akunSiswa?->siswa;

        $jumlahBeasiswa = $siswa?->beasiswa()->count() ?? 0;
        $jumlahPrestasi = $siswa?->prestasi()->count() ?? 0;
        $jumlahKesejahteraan = $siswa?->kesejahteraan()->count() ?? 0;

        $dataLengkap = $siswa
            ? ($siswa->nama_lengkap && $siswa->nis && $siswa->nisn && $siswa->rombel_id && $siswa->rayon_id)
            : false;
        $sudahMengisi = $dataLengkap ? 1 : 0;

        $dokumenSiswa = $siswa?->dokumenSiswa;
        $jumlahDokumenDiunggah = $dokumenSiswa
            ? collect([
                $dokumenSiswa->akte_kelahiran,
                $dokumenSiswa->kartu_keluarga,
                $dokumenSiswa->ktp_ayah,
                $dokumenSiswa->ktp_ibu,
                $dokumenSiswa->ijazah_sd,
                $dokumenSiswa->ijazah_smp,
            ])->filter()->count()
            : 0;
        $sudahMengunggahDokumen = $jumlahDokumenDiunggah > 0 ? 1 : 0;

        $periodik = $siswa?->periodik;
        $periodikLengkap = $periodik
            ? ($periodik->tinggi_badan_cm && $periodik->berat_badan_kg && $periodik->lingkar_kepala_cm && $periodik->jarak_sebenarnya_km)
            : false;
        $sudahIsiPeriodik = $periodikLengkap ? 1 : 0;

        $orangTua = $siswa?->orangTua;
        $sudahIsiOrangTua = $orangTua
            ? ($orangTua->nama_ayah || $orangTua->nama_ibu || $orangTua->nama_wali ? 1 : 0)
            : 0;

        $registrasi = $siswa?->registrasiSiswa;
        $sudahIsiRegistrasi = $registrasi
            ? ($registrasi->jenis_pendaftaran || $registrasi->tanggal_masuk || $registrasi->sekolah_asal ? 1 : 0)
            : 0;

        $kontak = $siswa?->kontakSiswa;
        $sudahIsiKontak = $kontak
            ? ($kontak->no_hp || $kontak->email || $kontak->alamat_jalan ? 1 : 0)
            : 0;

        return view('home', [
            'role' => 'siswa',
            'user' => $user,
            'siswa' => $siswa,
            'jumlahBeasiswa' => $jumlahBeasiswa,
            'jumlahPrestasi' => $jumlahPrestasi,
            'jumlahKesejahteraan' => $jumlahKesejahteraan,
            'sudahMengisi' => $sudahMengisi,
            'dokumenSiswa' => $dokumenSiswa,
            'sudahMengunggahDokumen' => $sudahMengunggahDokumen,
            'periodik' => $periodik,
            'sudahIsiPeriodik' => $sudahIsiPeriodik,
            'orangTua' => $orangTua,
            'sudahIsiOrangTua' => $sudahIsiOrangTua,
            'registrasi' => $registrasi,
            'sudahIsiRegistrasi' => $sudahIsiRegistrasi,
            'kontak' => $kontak,
            'sudahIsiKontak' => $sudahIsiKontak,
        ]);
    }

}
