<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class MasterSiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $siswa = DB::table('akun_siswa')
            ->join('peserta_didik', 'akun_siswa.peserta_didik_id', '=', 'peserta_didik.id')
            ->leftJoin('dokumen_siswa', 'peserta_didik.id', '=', 'dokumen_siswa.peserta_didik_id')
            ->leftJoin('data_periodik', 'peserta_didik.id', '=', 'data_periodik.peserta_didik_id')
            ->leftJoin('beasiswa', 'peserta_didik.id', '=', 'beasiswa.peserta_didik_id')
            ->leftJoin('prestasi', 'peserta_didik.id', '=', 'prestasi.peserta_didik_id')
            ->leftJoin('orang_tua', 'peserta_didik.id', '=', 'orang_tua.peserta_didik_id')
            ->leftJoin('registrasi_peserta_didik', 'peserta_didik.id', '=', 'registrasi_peserta_didik.peserta_didik_id')
            ->leftJoin('kesejahteraan', 'peserta_didik.id', '=', 'kesejahteraan.peserta_didik_id')
            ->leftJoin('kontak_peserta_didik', 'peserta_didik.id', '=', 'kontak_peserta_didik.peserta_didik_id')
            ->leftJoin('rayon', 'peserta_didik.rayon_id', '=', 'rayon.id')
            ->leftJoin('rombel', 'peserta_didik.rombel_id', '=', 'rombel.id')
            ->where('akun_siswa.email', $user->email)
            ->select(
                
                // siswa
                'peserta_didik.id',
                'peserta_didik.nama_lengkap',
                'peserta_didik.jenis_kelamin',
                'peserta_didik.nis',
                'peserta_didik.nisn',
                'peserta_didik.nik',
                'peserta_didik.no_kk',
                'peserta_didik.tempat_lahir',
                'peserta_didik.tanggal_lahir',
                'peserta_didik.agama',
                'peserta_didik.kewarganegaraan',
                'peserta_didik.negara_asal',
                'peserta_didik.berkebutuhan_khusus',
                'rayon.nama_rayon as nama_rayon',
                'rombel.nama_rombel as nama_rombel',

                // dokumen
                'dokumen_siswa.akte_kelahiran',
                'dokumen_siswa.kartu_keluarga',
                'dokumen_siswa.ktp_ayah',
                'dokumen_siswa.ktp_ibu',
                'dokumen_siswa.ijazah_sd',
                'dokumen_siswa.ijazah_smp',

                // akun
                'akun_siswa.email as akun_email',
                'akun_siswa.password as akun_password',

                // periodik
                'data_periodik.tinggi_badan_cm',
                'data_periodik.berat_badan_kg',
                'data_periodik.lingkar_kepala_cm',
                'data_periodik.jarak_ke_sekolah',
                'data_periodik.jarak_sebenarnya_km',
                'data_periodik.waktu_tempuh_jam',
                'data_periodik.waktu_tempuh_menit',
                'data_periodik.jumlah_saudara',

                // beasiswa
                'beasiswa.jenis_beasiswa',
                'beasiswa.keterangan as beasiswa_keterangan',
                'beasiswa.tahun_mulai as beasiswa_tahun_mulai',
                'beasiswa.tahun_selesai as beasiswa_tahun_selesai',

                // prestasi
                'prestasi.jenis_prestasi',
                'prestasi.tingkat_prestasi',
                'prestasi.nama_prestasi',
                'prestasi.tahun_prestasi',
                'prestasi.penyelenggara',
                'prestasi.peringkat',

                // orang tua
                'orang_tua.nama_ayah',
                'orang_tua.nik_ayah',
                'orang_tua.tahun_lahir_ayah',
                'orang_tua.pendidikan_ayah',
                'orang_tua.pekerjaan_ayah',
                'orang_tua.penghasilan_ayah',
                'orang_tua.kebutuhan_khusus_ayah',
                'orang_tua.nama_ibu',
                'orang_tua.nik_ibu',
                'orang_tua.tahun_lahir_ibu',
                'orang_tua.pendidikan_ibu',
                'orang_tua.pekerjaan_ibu',
                'orang_tua.penghasilan_ibu',
                'orang_tua.kebutuhan_khusus_ibu',
                'orang_tua.nama_wali',
                'orang_tua.nik_wali',
                'orang_tua.tahun_lahir_wali',
                'orang_tua.pendidikan_wali',
                'orang_tua.pekerjaan_wali',
                'orang_tua.penghasilan_wali',

                // registraasi
                'registrasi_peserta_didik.jenis_pendaftaran',
                'registrasi_peserta_didik.tanggal_masuk',
                'registrasi_peserta_didik.sekolah_asal',
                'registrasi_peserta_didik.no_peserta_un',
                'registrasi_peserta_didik.no_seri_ijazah',
                'registrasi_peserta_didik.no_skhun',

                //kesejahteraan
                'kesejahteraan.jenis_kesejahteraan',
                'kesejahteraan.no_kartu',
                'kesejahteraan.nama_di_kartu',

                //kontak
                'kontak_peserta_didik.no_hp',
                'kontak_peserta_didik.email as kontak_email',
                'kontak_peserta_didik.alamat_jalan',
                'kontak_peserta_didik.rt',
                'kontak_peserta_didik.rw',
                'kontak_peserta_didik.kelurahan',
                'kontak_peserta_didik.kecamatan',
                'kontak_peserta_didik.kode_pos',
                'kontak_peserta_didik.tempat_tinggal',
                'kontak_peserta_didik.moda_transportasi',
                'kontak_peserta_didik.anak_ke'
            )
            ->first();

        return view('master-siswa.index', compact('siswa'));
    }

    public function cetakPDF($id)
    {
        $siswa = DB::table('peserta_didik')
        ->leftJoin('akun_siswa', 'akun_siswa.peserta_didik_id', '=', 'peserta_didik.id')
        ->leftJoin('dokumen_siswa', 'peserta_didik.id', '=', 'dokumen_siswa.peserta_didik_id')
        ->leftJoin('data_periodik', 'peserta_didik.id', '=', 'data_periodik.peserta_didik_id')
        ->leftJoin('beasiswa', 'peserta_didik.id', '=', 'beasiswa.peserta_didik_id')
        ->leftJoin('prestasi', 'peserta_didik.id', '=', 'prestasi.peserta_didik_id')
        ->leftJoin('orang_tua', 'peserta_didik.id', '=', 'orang_tua.peserta_didik_id')
        ->leftJoin('registrasi_peserta_didik', 'peserta_didik.id', '=', 'registrasi_peserta_didik.peserta_didik_id')
        ->leftJoin('kesejahteraan', 'peserta_didik.id', '=', 'kesejahteraan.peserta_didik_id')
        ->leftJoin('kontak_peserta_didik', 'peserta_didik.id', '=', 'kontak_peserta_didik.peserta_didik_id')
        ->leftJoin('rayon', 'peserta_didik.rayon_id', '=', 'rayon.id')
        ->leftJoin('rombel', 'peserta_didik.rombel_id', '=', 'rombel.id')
        ->where('peserta_didik.id', $id)
        ->select(
            // siswa
            'peserta_didik.id',
            'peserta_didik.nama_lengkap',
            'peserta_didik.jenis_kelamin',
            'peserta_didik.nis',
            'peserta_didik.nisn',
            'peserta_didik.nik',
            'peserta_didik.no_kk',
            'peserta_didik.tempat_lahir',
            'peserta_didik.tanggal_lahir',
            'peserta_didik.agama',
            'rayon.nama_rayon',
            'rombel.nama_rombel',
            'peserta_didik.kewarganegaraan',
            'peserta_didik.negara_asal',
            'peserta_didik.berkebutuhan_khusus',

            // dokumen
            'dokumen_siswa.akte_kelahiran',
            'dokumen_siswa.kartu_keluarga',
            'dokumen_siswa.ktp_ayah',
            'dokumen_siswa.ktp_ibu',
            'dokumen_siswa.ijazah_sd',
            'dokumen_siswa.ijazah_smp',

            // akun
            'akun_siswa.email as akun_email',
            'akun_siswa.password as akun_password',

            // periodik
            'data_periodik.tinggi_badan_cm',
            'data_periodik.berat_badan_kg',
            'data_periodik.lingkar_kepala_cm',
            'data_periodik.jarak_ke_sekolah',
            'data_periodik.jarak_sebenarnya_km',
            'data_periodik.waktu_tempuh_jam',
            'data_periodik.waktu_tempuh_menit',
            'data_periodik.jumlah_saudara',

            // beasiswa
            'beasiswa.jenis_beasiswa',
            'beasiswa.keterangan as beasiswa_keterangan',
            'beasiswa.tahun_mulai as beasiswa_tahun_mulai',
            'beasiswa.tahun_selesai as beasiswa_tahun_selesai',

            // prestasi
            'prestasi.jenis_prestasi',
            'prestasi.tingkat_prestasi',
            'prestasi.nama_prestasi',
            'prestasi.tahun_prestasi',
            'prestasi.penyelenggara',
            'prestasi.peringkat',

            // orang tua
            'orang_tua.nama_ayah',
            'orang_tua.nik_ayah',
            'orang_tua.tahun_lahir_ayah',
            'orang_tua.pendidikan_ayah',
            'orang_tua.pekerjaan_ayah',
            'orang_tua.penghasilan_ayah',
            'orang_tua.kebutuhan_khusus_ayah',
            'orang_tua.nama_ibu',
            'orang_tua.nik_ibu',
            'orang_tua.tahun_lahir_ibu',
            'orang_tua.pendidikan_ibu',
            'orang_tua.pekerjaan_ibu',
            'orang_tua.penghasilan_ibu',
            'orang_tua.kebutuhan_khusus_ibu',
            'orang_tua.nama_wali',
            'orang_tua.nik_wali',
            'orang_tua.tahun_lahir_wali',
            'orang_tua.pendidikan_wali',
            'orang_tua.pekerjaan_wali',
            'orang_tua.penghasilan_wali',

            // registrasi
            'registrasi_peserta_didik.jenis_pendaftaran',
            'registrasi_peserta_didik.tanggal_masuk',
            'registrasi_peserta_didik.sekolah_asal',
            'registrasi_peserta_didik.no_peserta_un',
            'registrasi_peserta_didik.no_seri_ijazah',
            'registrasi_peserta_didik.no_skhun',

            // kesejahteraan
            'kesejahteraan.jenis_kesejahteraan',
            'kesejahteraan.no_kartu',
            'kesejahteraan.nama_di_kartu',

            // kontak
            'kontak_peserta_didik.no_hp',
            'kontak_peserta_didik.email as kontak_email',
            'kontak_peserta_didik.alamat_jalan',
            'kontak_peserta_didik.rt',
            'kontak_peserta_didik.rw',
            'kontak_peserta_didik.kelurahan',
            'kontak_peserta_didik.kecamatan',
            'kontak_peserta_didik.kode_pos',
            'kontak_peserta_didik.tempat_tinggal',
            'kontak_peserta_didik.moda_transportasi',
            'kontak_peserta_didik.anak_ke'
        )
        ->first();

        if (!$siswa) {
            abort(404, 'Data siswa tidak ditemukan.');
        }

        $pdf = Pdf::loadView('master-siswa.cetak', compact('siswa'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('data-siswa.pdf');
    }


}
