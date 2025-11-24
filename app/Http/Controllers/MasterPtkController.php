<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class MasterPtkController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        $ptk = DB::table('akun_ptk')
        ->join('ptk', 'akun_ptk.ptk_id', '=', 'ptk.id')
        ->leftJoin('dokumen_ptk', 'ptk.id', '=', 'dokumen_ptk.ptk_id')
        ->leftJoin('kontak_ptk', 'ptk.id', '=', 'kontak_ptk.ptk_id')
        ->leftJoin('anak', 'ptk.id', '=', 'anak.ptk_id')
        ->leftJoin('keluarga_ptk', 'ptk.id', '=', 'keluarga_ptk.ptk_id')
        ->leftJoin('tunjangan', 'ptk.id', '=', 'tunjangan.ptk_id')
        ->leftJoin('kesejahteraan_ptk', 'ptk.id', '=', 'kesejahteraan_ptk.ptk_id')
        ->leftJoin('penugasan', 'ptk.id', '=', 'penugasan.ptk_id')
        ->leftJoin('kepegawaian', 'ptk.id', '=', 'kepegawaian.ptk_id')
        ->leftJoin('tugas_tambahan', 'ptk.id', '=', 'tugas_tambahan.ptk_id')
        ->leftJoin('riwayat_gaji', 'ptk.id', '=', 'riwayat_gaji.ptk_id')
        ->leftJoin('riwayat_karir', 'ptk.id', '=', 'riwayat_karir.ptk_id')
        ->leftJoin('riwayat_jabatan', 'ptk.id', '=', 'riwayat_jabatan.ptk_id')
        ->leftJoin('riwayat_kepangkatan', 'ptk.id', '=', 'riwayat_kepangkatan.ptk_id')
        ->leftJoin('riwayat_jabatan_fungsional', 'ptk.id', '=', 'riwayat_jabatan_fungsional.ptk_id')
        ->leftJoin('diklat', 'ptk.id', '=', 'diklat.ptk_id')
        ->leftJoin('nilai_test', 'ptk.id', '=', 'nilai_test.ptk_id')
        ->leftJoin('pendidikan_ptk', 'ptk.id', '=', 'pendidikan_ptk.ptk_id')
        ->leftJoin('sertifikat', 'ptk.id', '=', 'sertifikat.ptk_id')
        ->leftJoin('beasiswa_ptk', 'ptk.id', '=', 'beasiswa_ptk.ptk_id')
        ->leftJoin('penghargaan', 'ptk.id', '=', 'penghargaan.ptk_id')
        ->leftJoin('kompetensi', 'ptk.id', '=', 'kompetensi.ptk_id')
        ->leftJoin('kompetensi_khusus', 'ptk.id', '=', 'kompetensi_khusus.ptk_id')
        ->leftJoin('semester', 'tunjangan.semester_id', '=', 'semester.id')
        ->where('akun_ptk.email', $user->email)
        ->select(

            //ptk
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
            'ptk.negara_asal',

            //dokumen
            'dokumen_ptk.akte_kelahiran',
            'dokumen_ptk.kartu_keluarga',
            'dokumen_ptk.ktp',
            'dokumen_ptk.ijazah_sd',
            'dokumen_ptk.ijazah_smp',
            'dokumen_ptk.ijazah_sma',
            'dokumen_ptk.ijazah_s1',
            'dokumen_ptk.ijazah_s2',
            'dokumen_ptk.ijazah_s3',

            //akun
            'akun_ptk.email as akun_email',
            'akun_ptk.password as akun_password',

            //anak
            'anak.nama_anak',
            'anak.status_anak',
            'anak.jenjang',
            'anak.nisn',
            'anak.jenis_kelamin',
            'anak.tempat_lahir',
            'anak.tanggal_lahir',
            'anak.tahun_masuk',

            //keluarga
            'keluarga_ptk.no_kk',
            'keluarga_ptk.status_perkawinan',
            'keluarga_ptk.nama_suami_istri',
            'keluarga_ptk.nip_suami_istri',
            'keluarga_ptk.pekerjaan_suami_istri',

            //tunjangan
            'tunjangan.jenis_tunjangan',
            'tunjangan.nama_tunjangan',
            'tunjangan.instansi',
            'tunjangan.sk_tunjangan',
            'tunjangan.tgl_sk_tunjangan',
            'semester.nama_semester as nama_semester',
            'tunjangan.sumber_dana',
            'tunjangan.dari_tahun',
            'tunjangan.sampai_tahun',
            'tunjangan.nominal',
            'tunjangan.status',

            //kesejahteraan
            'kesejahteraan_ptk.jenis_kesejahteraan',
            'kesejahteraan_ptk.nama',
            'kesejahteraan_ptk.penyelenggara',
            'kesejahteraan_ptk.dari_tahun',
            'kesejahteraan_ptk.sampai_tahun',
            'kesejahteraan_ptk.status',

            //penugasan
            'penugasan.nomor_surat_tugas',
            'penugasan.tanggal_surat_tugas',
            'penugasan.tmt_tugas',
            'penugasan.status_sekolah_induk',

            //kepegawaian
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
            'kepegawaian.kartu_keluarga',

            //tugas tambahan
            'tugas_tambahan.jabatan_ptk',
            'tugas_tambahan.prasarana',
            'tugas_tambahan.nomor_sk',
            'tugas_tambahan.tmt_tambahan',
            'tugas_tambahan.tst_tambahan',

            //riwayat gaji
            'riwayat_gaji.pangkat_golongan',
            'riwayat_gaji.nomor_sk',
            'riwayat_gaji.tanggal_sk',
            'riwayat_gaji.tmt_kgb',
            'riwayat_gaji.masa_kerja_thn',
            'riwayat_gaji.masa_kerja_bln',
            'riwayat_gaji.gaji_pokok',

            //riwayat karir
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
            'riwayat_karir.ttd_sk_kerja',

            //riwayat jabatan
            'riwayat_jabatan.jabatan_ptk',
            'riwayat_jabatan.sk_jabatan',
            'riwayat_jabatan.tmt_jabatan',

            //riwayat kepangkatan
            'riwayat_kepangkatan.pangkat_golongan',
            'riwayat_kepangkatan.nomor_sk',
            'riwayat_kepangkatan.tanggal_sk',
            'riwayat_kepangkatan.tmt_pangkat',
            'riwayat_kepangkatan.masa_kerja_thn',
            'riwayat_kepangkatan.masa_kerja_bln',

            //jabatan fungsional
            'riwayat_jabatan_fungsional.jabatan_fungsional',
            'riwayat_jabatan_fungsional.sk_jabfung',
            'riwayat_jabatan_fungsional.tmt_jabatan',

            //diklat
            'diklat.jenis_diklat',
            'diklat.nama_diklat',
            'diklat.no_sertifikat',
            'diklat.penyelenggara',
            'diklat.tahun',
            'diklat.peran',
            'diklat.tingkat',

            //nilai test
            'nilai_test.jenis_test',
            'nilai_test.nama_test',
            'nilai_test.penyelenggara',
            'nilai_test.tahun',
            'nilai_test.skor',
            'nilai_test.nomor_peserta',

            //pendidikan ptk
            'pendidikan_ptk.bidang_studi',
            'pendidikan_ptk.jenjang_pendidikan',
            'pendidikan_ptk.gelar_akademik',
            'pendidikan_ptk.satuan_pendidikan_formal',
            'pendidikan_ptk.fakultas',
            'pendidikan_ptk.kependidikan',
            'pendidikan_ptk.tahun_masuk',
            'pendidikan_ptk.tahun_lulus',
            'pendidikan_ptk.nomor_induk',
            'pendidikan_ptk.masih_studi',
            'pendidikan_ptk.semester',
            'pendidikan_ptk.rata_rata_ujian',

            //sertifikat
            'sertifikat.jenis_sertifikasi',
            'sertifikat.nomor_sertifikat',
            'sertifikat.tahun_sertifikasi',
            'sertifikat.bidang_studi',
            'sertifikat.nrg',
            'sertifikat.nomor_peserta',

            //beasiswa
            'beasiswa_ptk.jenis_beasiswa',
            'beasiswa_ptk.keterangan',
            'beasiswa_ptk.tahun_mulai',
            'beasiswa_ptk.tahun_akhir',
            'beasiswa_ptk.masih_menerima',

            //penghargaan
            'penghargaan.tingkat_penghargaan',
            'penghargaan.jenis_penghargaan',
            'penghargaan.nama_penghargaan',
            'penghargaan.tahun',
            'penghargaan.instansi',

            //kompetensi
            'kompetensi.bidang_studi',
            'kompetensi.urutan',

            //kompetensi khusus
            'kompetensi_khusus.punya_lisensi_kepala_sekolah',
            'kompetensi_khusus.nomor_unik_kepala_sekolah',
            'kompetensi_khusus.keahlian_lab_oratorium',
            'kompetensi_khusus.mampu_menangani',
            'kompetensi_khusus.keahlian_braile',
            'kompetensi_khusus.keahlian_bahasa_isyarat'
        )
        ->first();
        return view('master-ptk.index', compact('ptk'));
    }

    public function cetakPdf($id)
    {
        $ptk = DB::table('ptk')
        ->leftJoin('akun_ptk', 'akun_ptk.ptk_id', '=', 'ptk.id')
        ->leftJoin('dokumen_ptk', 'ptk.id', '=', 'dokumen_ptk.ptk_id')
        ->leftJoin('kontak_ptk', 'ptk.id', '=', 'kontak_ptk.ptk_id')
        ->leftJoin('anak', 'ptk.id', '=', 'anak.ptk_id')
        ->leftJoin('keluarga_ptk', 'ptk.id', '=', 'keluarga_ptk.ptk_id')
        ->leftJoin('tunjangan', 'ptk.id', '=', 'tunjangan.ptk_id')
        ->leftJoin('kesejahteraan_ptk', 'ptk.id', '=', 'kesejahteraan_ptk.ptk_id')
        ->leftJoin('penugasan', 'ptk.id', '=', 'penugasan.ptk_id')
        ->leftJoin('kepegawaian', 'ptk.id', '=', 'kepegawaian.ptk_id')
        ->leftJoin('tugas_tambahan', 'ptk.id', '=', 'tugas_tambahan.ptk_id')
        ->leftJoin('riwayat_gaji', 'ptk.id', '=', 'riwayat_gaji.ptk_id')
        ->leftJoin('riwayat_karir', 'ptk.id', '=', 'riwayat_karir.ptk_id')
        ->leftJoin('riwayat_jabatan', 'ptk.id', '=', 'riwayat_jabatan.ptk_id')
        ->leftJoin('riwayat_kepangkatan', 'ptk.id', '=', 'riwayat_kepangkatan.ptk_id')
        ->leftJoin('riwayat_jabatan_fungsional', 'ptk.id', '=', 'riwayat_jabatan_fungsional.ptk_id')
        ->leftJoin('diklat', 'ptk.id', '=', 'diklat.ptk_id')
        ->leftJoin('nilai_test', 'ptk.id', '=', 'nilai_test.ptk_id')
        ->leftJoin('pendidikan_ptk', 'ptk.id', '=', 'pendidikan_ptk.ptk_id')
        ->leftJoin('sertifikat', 'ptk.id', '=', 'sertifikat.ptk_id')
        ->leftJoin('beasiswa_ptk', 'ptk.id', '=', 'beasiswa_ptk.ptk_id')
        ->leftJoin('penghargaan', 'ptk.id', '=', 'penghargaan.ptk_id')
        ->leftJoin('kompetensi', 'ptk.id', '=', 'kompetensi.ptk_id')
        ->leftJoin('kompetensi_khusus', 'ptk.id', '=', 'kompetensi_khusus.ptk_id')
        ->leftJoin('semester', 'tunjangan.semester_id', '=', 'semester.id')
        ->where('ptk.id', $id)
        ->select(

            //ptk
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
            'ptk.negara_asal',

            //dokumen
            'dokumen_ptk.akte_kelahiran',
            'dokumen_ptk.kartu_keluarga',
            'dokumen_ptk.ktp',
            'dokumen_ptk.ijazah_sd',
            'dokumen_ptk.ijazah_smp',
            'dokumen_ptk.ijazah_sma',
            'dokumen_ptk.ijazah_s1',
            'dokumen_ptk.ijazah_s2',
            'dokumen_ptk.ijazah_s3',

            //akun
            'akun_ptk.email as akun_email',
            'akun_ptk.password as akun_password',

            //anak
            'anak.nama_anak',
            'anak.status_anak',
            'anak.jenjang',
            'anak.nisn',
            'anak.jenis_kelamin',
            'anak.tempat_lahir',
            'anak.tanggal_lahir',
            'anak.tahun_masuk',

            //keluarga
            'keluarga_ptk.no_kk',
            'keluarga_ptk.status_perkawinan',
            'keluarga_ptk.nama_suami_istri',
            'keluarga_ptk.nip_suami_istri',
            'keluarga_ptk.pekerjaan_suami_istri',

            //tunjangan
            'tunjangan.jenis_tunjangan',
            'tunjangan.nama_tunjangan',
            'tunjangan.instansi',
            'tunjangan.sk_tunjangan',
            'tunjangan.tgl_sk_tunjangan',
            'semester.nama_semester as nama_semester',
            'tunjangan.sumber_dana',
            'tunjangan.dari_tahun',
            'tunjangan.sampai_tahun',
            'tunjangan.nominal',
            'tunjangan.status',

            //kesejahteraan
            'kesejahteraan_ptk.jenis_kesejahteraan',
            'kesejahteraan_ptk.nama',
            'kesejahteraan_ptk.penyelenggara',
            'kesejahteraan_ptk.dari_tahun',
            'kesejahteraan_ptk.sampai_tahun',
            'kesejahteraan_ptk.status',

            //penugasan
            'penugasan.nomor_surat_tugas',
            'penugasan.tanggal_surat_tugas',
            'penugasan.tmt_tugas',
            'penugasan.status_sekolah_induk',

            //kepegawaian
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
            'kepegawaian.kartu_keluarga',

            //tugas tambahan
            'tugas_tambahan.jabatan_ptk',
            'tugas_tambahan.prasarana',
            'tugas_tambahan.nomor_sk',
            'tugas_tambahan.tmt_tambahan',
            'tugas_tambahan.tst_tambahan',

            //riwayat gaji
            'riwayat_gaji.pangkat_golongan',
            'riwayat_gaji.nomor_sk',
            'riwayat_gaji.tanggal_sk',
            'riwayat_gaji.tmt_kgb',
            'riwayat_gaji.masa_kerja_thn',
            'riwayat_gaji.masa_kerja_bln',
            'riwayat_gaji.gaji_pokok',

            //riwayat karir
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
            'riwayat_karir.ttd_sk_kerja',

            //riwayat jabatan
            'riwayat_jabatan.jabatan_ptk',
            'riwayat_jabatan.sk_jabatan',
            'riwayat_jabatan.tmt_jabatan',

            //riwayat kepangkatan
            'riwayat_kepangkatan.pangkat_golongan',
            'riwayat_kepangkatan.nomor_sk',
            'riwayat_kepangkatan.tanggal_sk',
            'riwayat_kepangkatan.tmt_pangkat',
            'riwayat_kepangkatan.masa_kerja_thn',
            'riwayat_kepangkatan.masa_kerja_bln',

            //jabatan fungsional
            'riwayat_jabatan_fungsional.jabatan_fungsional',
            'riwayat_jabatan_fungsional.sk_jabfung',
            'riwayat_jabatan_fungsional.tmt_jabatan',

            //diklat
            'diklat.jenis_diklat',
            'diklat.nama_diklat',
            'diklat.no_sertifikat',
            'diklat.penyelenggara',
            'diklat.tahun',
            'diklat.peran',
            'diklat.tingkat',

            //nilai test
            'nilai_test.jenis_test',
            'nilai_test.nama_test',
            'nilai_test.penyelenggara',
            'nilai_test.tahun',
            'nilai_test.skor',
            'nilai_test.nomor_peserta',

            //pendidikan ptk
            'pendidikan_ptk.bidang_studi',
            'pendidikan_ptk.jenjang_pendidikan',
            'pendidikan_ptk.gelar_akademik',
            'pendidikan_ptk.satuan_pendidikan_formal',
            'pendidikan_ptk.fakultas',
            'pendidikan_ptk.kependidikan',
            'pendidikan_ptk.tahun_masuk',
            'pendidikan_ptk.tahun_lulus',
            'pendidikan_ptk.nomor_induk',
            'pendidikan_ptk.masih_studi',
            'pendidikan_ptk.semester',
            'pendidikan_ptk.rata_rata_ujian',

            //sertifikat
            'sertifikat.jenis_sertifikasi',
            'sertifikat.nomor_sertifikat',
            'sertifikat.tahun_sertifikasi',
            'sertifikat.bidang_studi',
            'sertifikat.nrg',
            'sertifikat.nomor_peserta',

            //beasiswa
            'beasiswa_ptk.jenis_beasiswa',
            'beasiswa_ptk.keterangan',
            'beasiswa_ptk.tahun_mulai',
            'beasiswa_ptk.tahun_akhir',
            'beasiswa_ptk.masih_menerima',

            //penghargaan
            'penghargaan.tingkat_penghargaan',
            'penghargaan.jenis_penghargaan',
            'penghargaan.nama_penghargaan',
            'penghargaan.tahun',
            'penghargaan.instansi',

            //kompetensi
            'kompetensi.bidang_studi',
            'kompetensi.urutan',

            //kompetensi khusus
            'kompetensi_khusus.punya_lisensi_kepala_sekolah',
            'kompetensi_khusus.nomor_unik_kepala_sekolah',
            'kompetensi_khusus.keahlian_lab_oratorium',
            'kompetensi_khusus.mampu_menangani',
            'kompetensi_khusus.keahlian_braile',
            'kompetensi_khusus.keahlian_bahasa_isyarat'
        )
        ->first();

        if (!$ptk) {
            abort(404, 'Data ptk tidak ditemukan.');
        }

        $pdf = Pdf::loadView('master-ptk.cetak', compact('ptk'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('data-ptk.pdf');
    }

}
