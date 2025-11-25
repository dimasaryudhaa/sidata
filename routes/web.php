<?php

use App\Http\Controllers\AkunPtkController;
use App\Http\Controllers\AkunSiswaController;
use App\Http\Controllers\AnakPtkController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KontakSiswaController;
use App\Http\Controllers\RayonController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\RombelController;
use App\Http\Controllers\PeriodikSiswaController;
use App\Http\Controllers\PtkController;
use App\Http\Controllers\BeasiswaSiswaController;
use App\Http\Controllers\PrestasiSiswaController;
use App\Http\Controllers\OrangTuaController;
use App\Http\Controllers\PendidikanPtkController;
use App\Http\Controllers\KontakPtkController;
use App\Http\Controllers\KeluargaPtkController;
use App\Http\Controllers\KepegawaianPtkController;
use App\Http\Controllers\BeasiswaPtkController;
use App\Http\Controllers\DiklatController;
use App\Http\Controllers\DokumenPtkController;
use App\Http\Controllers\DokumenSiswaController;
use App\Http\Controllers\KesejahteraanPtkController;
use App\Http\Controllers\KesejahteraanSiswaController;
use App\Http\Controllers\KompetensiKhususPtkController;
use App\Http\Controllers\KompetensiPtkController;
use App\Http\Controllers\MasterPtkController;
use App\Http\Controllers\MasterSiswaController;
use App\Http\Controllers\NilaiTestController;
use App\Http\Controllers\PendaftaranKeluarController;
use App\Http\Controllers\PenghargaanController;
use App\Http\Controllers\PenugasanPtkController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrasiSiswaController;
use App\Http\Controllers\RiwayatGajiController;
use App\Http\Controllers\RiwayatJabatanController;
use App\Http\Controllers\RiwayatJabatanFungsionalController;
use App\Http\Controllers\RiwayatKarirController;
use App\Http\Controllers\RiwayatKepangkatanController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SertifikatPtkController;
use App\Http\Controllers\TugasTambahanController;
use App\Http\Controllers\TunjanganController;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
    Route::get('/admin/dashboard', [HomeController::class, 'admin'])
        ->name('admin.dashboard');
    Route::get('/ptk/dashboard', [HomeController::class, 'ptk'])
        ->name('ptk.dashboard');
    Route::get('/siswa/dashboard', [HomeController::class, 'siswa'])
        ->name('siswa.dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {

        //siswa
        Route::resource('siswa', SiswaController::class);
        Route::resource('akun-siswa', AkunSiswaController::class);
        Route::resource('dokumen-siswa', DokumenSiswaController::class);
        Route::resource('periodik', PeriodikSiswaController::class);
        Route::resource('beasiswa', BeasiswaSiswaController::class);
        Route::resource('prestasi', PrestasiSiswaController::class);
        Route::resource('orang-tua', OrangTuaController::class);
        Route::resource('registrasi-siswa', RegistrasiSiswaController::class);
        Route::resource('kesejahteraan-siswa', KesejahteraanSiswaController::class);
        Route::resource('kontak-siswa', KontakSiswaController::class);

        //ptk
        Route::resource('ptk', PtkController::class);
        Route::resource('akun-ptk', AkunPtkController::class);
        Route::resource('kontak-ptk', KontakPtkController::class);
        Route::resource('dokumen-ptk', DokumenPtkController::class);
        Route::resource('anak-ptk', AnakPtkController::class);
        Route::resource('keluarga-ptk', KeluargaPtkController::class);
        Route::resource('tunjangan', TunjanganController::class);
        Route::resource('kesejahteraan-ptk', KesejahteraanPtkController::class);
        Route::resource('penugasan-ptk', PenugasanPtkController::class);
        Route::resource('kepegawaian-ptk', KepegawaianPtkController::class);
        Route::resource('tugas-tambahan', TugasTambahanController::class);
        Route::resource('riwayat-gaji', RiwayatGajiController::class);
        Route::resource('riwayat-karir', RiwayatKarirController::class);
        Route::resource('riwayat-jabatan', RiwayatJabatanController::class);
        Route::resource('riwayat-kepangkatan', RiwayatKepangkatanController::class);
        Route::resource('riwayat-jabatan-fungsional', RiwayatJabatanFungsionalController::class);
        Route::resource('diklat', DiklatController::class);
        Route::resource('nilai-test', NilaiTestController::class);
        Route::resource('pendidikan-ptk', PendidikanPtkController::class);
        Route::resource('sertifikat-ptk', SertifikatPtkController::class);
        Route::resource('beasiswa-ptk', BeasiswaPtkController::class);
        Route::resource('penghargaan', PenghargaanController::class);
        Route::resource('kompetensi-ptk', KompetensiPtkController::class);
        Route::resource('kompetensi-khusus-ptk', KompetensiKhususPtkController::class);
        Route::resource('tunjangan', TunjanganController::class);

        //akademik
        Route::resource('rayon', RayonController::class);
        Route::resource('jurusan', JurusanController::class);
        Route::resource('rombel', RombelController::class);
        Route::resource('semester', SemesterController::class);
    });

    Route::prefix('ptk')->name('ptk.')->group(function () {
        //ptk
        Route::resource('master-ptk', MasterPtkController::class);
        Route::get('/master-ptk/{id}/cetak', [MasterPtkController::class, 'cetakPDF'])
        ->name('master-ptk.cetak');
        Route::resource('ptk', PtkController::class);
        Route::resource('akun-ptk', AkunPtkController::class);
        Route::resource('kontak-ptk', KontakPtkController::class);
        Route::resource('dokumen-ptk', DokumenPtkController::class);
        Route::resource('anak-ptk', AnakPtkController::class);
        Route::resource('keluarga-ptk', KeluargaPtkController::class);
        Route::resource('tunjangan', TunjanganController::class);
        Route::resource('kesejahteraan-ptk', KesejahteraanPtkController::class);
        Route::resource('penugasan-ptk', PenugasanPtkController::class);
        Route::resource('kepegawaian-ptk', KepegawaianPtkController::class);
        Route::resource('tugas-tambahan', TugasTambahanController::class);
        Route::resource('riwayat-gaji', RiwayatGajiController::class);
        Route::resource('riwayat-karir', RiwayatKarirController::class);
        Route::resource('riwayat-jabatan', RiwayatJabatanController::class);
        Route::resource('riwayat-kepangkatan', RiwayatKepangkatanController::class);
        Route::resource('riwayat-jabatan-fungsional', RiwayatJabatanFungsionalController::class);
        Route::resource('diklat', DiklatController::class);
        Route::resource('nilai-test', NilaiTestController::class);
        Route::resource('pendidikan-ptk', PendidikanPtkController::class);
        Route::resource('sertifikat-ptk', SertifikatPtkController::class);
        Route::resource('beasiswa-ptk', BeasiswaPtkController::class);
        Route::resource('penghargaan', PenghargaanController::class);
        Route::resource('kompetensi-ptk', KompetensiPtkController::class);
        Route::resource('kompetensi-khusus-ptk', KompetensiKhususPtkController::class);
        Route::resource('tunjangan', TunjanganController::class);
    });

    Route::prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/master-siswa/{id}/cetak', [MasterSiswaController::class, 'cetakPDF'])
            ->name('master-siswa.cetak');
        Route::resource('master-siswa', MasterSiswaController::class);
        Route::resource('siswa', SiswaController::class);
        Route::resource('akun-siswa', AkunSiswaController::class);
        Route::resource('dokumen-siswa', DokumenSiswaController::class);
        Route::resource('periodik', PeriodikSiswaController::class);
        Route::resource('beasiswa', BeasiswaSiswaController::class);
        Route::resource('prestasi', PrestasiSiswaController::class);
        Route::resource('orang-tua', OrangTuaController::class);
        Route::resource('registrasi-siswa', RegistrasiSiswaController::class);
        Route::resource('kesejahteraan-siswa', KesejahteraanSiswaController::class);
         Route::resource('kontak-siswa', KontakSiswaController::class);
    });


    //profile
    Route::resource('profile', ProfileController::class);
    Route::resource('pendaftaran-keluar', PendaftaranKeluarController::class);

    //akademik

});
