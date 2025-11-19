@extends('layouts.app')

@section('content')

<style>
    .table { border: 1px solid #dee2e6; width: 100%; }
    .table th, .table td { padding: 8px 12px; }
    .table th { font-weight: 600; width: 35%; }
</style>

<div class="container">
    <h3 class="mb-4 fw-bold text-center">Data Lengkap PTK</h3>

    <div class="text-end mb-3">
        <a href="{{ route('ptk.master-ptk.cetak', $ptk->id) }}"
           class="btn btn-success" target="_blank">
            <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
        </a>
    </div>

    @if($ptk)

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1">Data Pribadi</div>
        <table class="table table-bordered">
            <tr><th>Nama Lengkap</th><td>{{ $ptk->nama_lengkap }}</td></tr>
            <tr><th>NIK</th><td>{{ $ptk->nik }}</td></tr>
            <tr><th>Jenis Kelamin</th><td>{{ $ptk->jenis_kelamin }}</td></tr>
            <tr><th>Tempat, Tanggal Lahir</th>
                <td>{{ $ptk->tempat_lahir }}, {{ $ptk->tanggal_lahir }}</td>
            </tr>
            <tr><th>Nama Ibu Kandung</th><td>{{ $ptk->nama_ibu_kandung }}</td></tr>
            <tr><th>Agama</th><td>{{ $ptk->agama }}</td></tr>
            <tr><th>Kewarganegaraan</th><td>{{ $ptk->kewarganegaraan }}</td></tr>
            <tr><th>Negara Asal</th><td>{{ $ptk->negara_asal }}</td></tr>
            <tr><th>NPWP</th><td>{{ $ptk->npwp }}</td></tr>
            <tr><th>Nama Wajib Pajak</th><td>{{ $ptk->nama_wajib_pajak }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1">Akun PTK</div>
        <table class="table table-bordered">
            <tr><th>Email</th><td>{{ $ptk->akun_email }}</td></tr>
            <tr><th>Password</th><td>********</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1">Dokumen PTK</div>
        <table class="table table-bordered">
            @php
                $dok = [
                    'akte_kelahiran' => 'Akte Kelahiran',
                    'kartu_keluarga' => 'Kartu Keluarga',
                    'ktp' => 'KTP',
                    'ijazah_sd' => 'Ijazah SD',
                    'ijazah_smp' => 'Ijazah SMP',
                    'ijazah_sma' => 'Ijazah SMA',
                    'ijazah_s1' => 'Ijazah S1',
                    'ijazah_s2' => 'Ijazah S2',
                    'ijazah_s3' => 'Ijazah S3',
                ];
            @endphp

            @foreach($dok as $field => $label)
            <tr>
                <th>{{ $label }}</th>
                <td>
                    @if($ptk->$field)
                        <img src="{{ asset('storage/'.$ptk->$field) }}"
                             class="img-thumbnail" style="max-width:180px;">
                    @else
                        <span class="text-muted">Belum diunggah</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1"> Anak</div>
        <table class="table table-bordered">
            <tr><th>Nama Anak</th><td>{{ $ptk->nama_anak }}</td></tr>
            <tr><th>Status Anak</th><td>{{ $ptk->status_anak }}</td></tr>
            <tr><th>Jenjang</th><td>{{ $ptk->jenjang }}</td></tr>
            <tr><th>NISN</th><td>{{ $ptk->nisn }}</td></tr>
            <tr><th>Jenis Kelamin</th><td>{{ $ptk->jenis_kelamin }}</td></tr>
            <tr><th>Tempat, Tanggal Lahir</th>
                <td>{{ $ptk->tempat_lahir }}, {{ $ptk->tanggal_lahir }}</td>
            </tr>
            <tr><th>Tahun Masuk</th><td>{{ $ptk->tahun_masuk }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1"> Keluarga</div>
        <table class="table table-bordered">
            <tr><th>No KK</th><td>{{ $ptk->no_kk }}</td></tr>
            <tr><th>Status Perkawinan</th><td>{{ $ptk->status_perkawinan }}</td></tr>
            <tr><th>Nama Suami/Istri</th><td>{{ $ptk->nama_suami_istri }}</td></tr>
            <tr><th>NIP Suami/Istri</th><td>{{ $ptk->nip_suami_istri }}</td></tr>
            <tr><th>Pekerjaan Suami/Istri</th><td>{{ $ptk->pekerjaan_suami_istri }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1"> Tunjangan</div>
        <table class="table table-bordered">
            <tr><th>Jenis Tunjangan</th><td>{{ $ptk->jenis_tunjangan }}</td></tr>
            <tr><th>Nama Tunjangan</th><td>{{ $ptk->nama_tunjangan }}</td></tr>
            <tr><th>Instansi</th><td>{{ $ptk->instansi }}</td></tr>
            <tr><th>SK Tunjangan</th><td>{{ $ptk->sk_tunjangan }}</td></tr>
            <tr><th>Tanggal SK</th><td>{{ $ptk->tgl_sk_tunjangan }}</td></tr>
            <tr><th>Semester</th><td>{{ $ptk->nama_semester }}</td></tr>
            <tr><th>Sumber Dana</th><td>{{ $ptk->sumber_dana }}</td></tr>
            <tr><th>Dari Tahun</th><td>{{ $ptk->dari_tahun }}</td></tr>
            <tr><th>Sampai Tahun</th><td>{{ $ptk->sampai_tahun }}</td></tr>
            <tr><th>Nominal</th><td>{{ $ptk->nominal }}</td></tr>
            <tr><th>Status</th><td>{{ $ptk->status }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1"> Kesejahteraan</div>
        <table class="table table-bordered">
            <tr><th>Jenis Kesejahteraan</th><td>{{ $ptk->jenis_kesejahteraan }}</td></tr>
            <tr><th>Nama</th><td>{{ $ptk->nama }}</td></tr>
            <tr><th>Penyelenggara</th><td>{{ $ptk->penyelenggara }}</td></tr>
            <tr><th>Dari Tahun</th><td>{{ $ptk->dari_tahun }}</td></tr>
            <tr><th>Sampai Tahun</th><td>{{ $ptk->sampai_tahun }}</td></tr>
            <tr><th>Status</th><td>{{ $ptk->status }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1"> Penugasan</div>
        <table class="table table-bordered">
            <tr><th>Nomor Surat Tugas</th><td>{{ $ptk->nomor_surat_tugas }}</td></tr>
            <tr><th>Tanggal Surat Tugas</th><td>{{ $ptk->tanggal_surat_tugas }}</td></tr>
            <tr><th>Status Sekolah Induk</th><td>{{ $ptk->status_sekolah_induk }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1"> Kepegawaian</div>
        <table class="table table-bordered">
            <tr><th>Status Kepegawaian</th><td>{{ $ptk->status_kepegawaian }}</td></tr>
            <tr><th>NIP</th><td>{{ $ptk->nip }}</td></tr>
            <tr><th>NIY dan NIGK</th><td>{{ $ptk->niy_nigk }}</td></tr>
            <tr><th>NUPTK</th><td>{{ $ptk->nuptk }}</td></tr>
            <tr><th>Jenis Ptk</th><td>{{ $ptk->jenis_ptk }}</td></tr>
            <tr><th>SK Pengangkatan</th><td>{{ $ptk->sk_pengangkatan }}</td></tr>
            <tr><th>TMT Pengangkatan</th><td>{{ $ptk->tmt_pengangkatan }}</td></tr>
            <tr><th>Lembaga Pengangkatan</th><td>{{ $ptk->lembaga_pengangkat }}</td></tr>
            <tr><th>SK CPNS</th><td>{{ $ptk->sk_cpns }}</td></tr>
            <tr><th>TMT CPNS</th><td>{{ $ptk->tmt_pns }}</td></tr>
            <tr><th>Pangkat Golongan</th><td>{{ $ptk->pangkat_golongan }}</td></tr>
            <tr><th>Sumber Gaji</th><td>{{ $ptk->sumber_gaji }}</td></tr>
            <tr><th>Kartu Pegawai</th><td>{{ $ptk->kartu_pegawai }}</td></tr>
            <tr><th>Kartu Keluarga</th><td>{{ $ptk->kartu_keluarga }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1"> Tugas Tambahan</div>
        <table class="table table-bordered">
            <tr><th>Jabatan PTK</th><td>{{ $ptk->jabatan_ptk }}</td></tr>
            <tr><th>Prasana</th><td>{{ $ptk->prasarana }}</td></tr>
            <tr><th>Nomor SK</th><td>{{ $ptk->nomor_sk }}</td></tr>
            <tr><th>TMT Tambahan</th><td>{{ $ptk->tmt_tambahan }}</td></tr>
            <tr><th>TST Tambahan</th><td>{{ $ptk->tst_tambahan }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1"> Riwayat Gaji</div>
        <table class="table table-bordered">
            <tr><th>Pangkat Golongan</th><td>{{ $ptk->pangkat_golongan }}</td></tr>
            <tr><th>Nomor SK</th><td>{{ $ptk->nomor_sk }}</td></tr>
            <tr><th>TMT KGB</th><td>{{ $ptk->tmt_kgb }}</td></tr>
            <tr><th>Masa Kerja</th>
                <td>{{ $ptk->masa_kerja_thn }} Tahun {{ $ptk->masa_kerja_bln }} Bulan</td>
            </tr>
            <tr><th>Gaji Pokok</th><td>{{ $ptk->gaji_pokok }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1"> Riwayat Karir</div>
        <table class="table table-bordered">
            <tr><th>Jenjang Pendidikan</th><td>{{ $ptk->jenjang_pendidikan }}</td></tr>
            <tr><th>Jenis Lembaga</th><td>{{ $ptk->jenis_lembaga }}</td></tr>
            <tr><th>Status Kepegawaian</th><td>{{ $ptk->status_kepegawaian }}</td></tr>
            <tr><th>Jenis PTK</th><td>{{ $ptk->jenis_ptk }}</td></tr>
            <tr><th>Lembaga Pengangkat</th><td>{{ $ptk->lembaga_pengangkat }}</td></tr>
            <tr><th>NO SK Kerja</th><td>{{ $ptk->no_sk_kerja }}</td></tr>
            <tr><th>Tanggal SK Kerja</th><td>{{ $ptk->tgl_sk_kerja }}</td></tr>
            <tr><th>TMT Kerja</th><td>{{ $ptk->tmt_kerja }}</td></tr>
            <tr><th>TST Kerja</th><td>{{ $ptk->tst_kerja }}</td></tr>
            <tr><th>Tempat Kerja</th><td>{{ $ptk->tempat_kerja }}</td></tr>
            <tr><th>TTD SK Kerja</th><td>{{ $ptk->ttd_sk_kerja }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1"> Riwayat Jabatan</div>
        <table class="table table-bordered">
            <tr><th>Jabatan PTK</th><td>{{ $ptk->jabatan_ptk }}</td></tr>
            <tr><th>SK Jabatan</th><td>{{ $ptk->sk_jabatan }}</td></tr>
            <tr><th>TMT Jabatan</th><td>{{ $ptk->tmt_jabatan }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1"> Riwayat Kepangkatan</div>
        <table class="table table-bordered">
            <tr><th>Pangkat Golongan</th><td>{{ $ptk->pangkat_golongan }}</td></tr>
            <tr><th>Nomor SK</th><td>{{ $ptk->nomor_sk }}</td></tr>
            <tr><th>Tanggal SK</th><td>{{ $ptk->tanggal_sk }}</td></tr>
            <tr><th>TMT Pangkat</th><td>{{ $ptk->tmt_pangkat }}</td></tr>
            <tr><th>Masa Kerja</th>
                <td>{{ $ptk->masa_kerja_thn }} Tahun {{ $ptk->masa_kerja_bln }} Bulan</td>
            </tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1"> Riwayat Jabatan Fungsional</div>
        <table class="table table-bordered">
            <tr><th>Jabatan Fungsional</th><td>{{ $ptk->jabatan_fungsional }}</td></tr>
            <tr><th>SK Jabfung</th><td>{{ $ptk->sk_jabfung }}</td></tr>
            <tr><th>TMT Jabatan</th><td>{{ $ptk->tmt_jabatan }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1">Diklat</div>
        <table class="table table-bordered">
            <tr><th>Jenis Diklat</th><td>{{ $ptk->jenis_diklat }}</td></tr>
            <tr><th>Nama Diklat</th><td>{{ $ptk->nama_diklat }}</td></tr>
            <tr><th>No Sertifikat</th><td>{{ $ptk->no_sertifikat }}</td></tr>
            <tr><th>Penyelenggara</th><td>{{ $ptk->penyelenggara }}</td></tr>
            <tr><th>Tahun</th><td>{{ $ptk->tahun }}</td></tr>
            <tr><th>Peran</th><td>{{ $ptk->peran }}</td></tr>
            <tr><th>Tingkat</th><td>{{ $ptk->tingkat }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1">Nilai Test</div>
        <table class="table table-bordered">
            <tr><th>Jenis Test</th><td>{{ $ptk->jenis_test }}</td></tr>
            <tr><th>Nama Test</th><td>{{ $ptk->nama_test }}</td></tr>
            <tr><th>Penyelenggara</th><td>{{ $ptk->penyelenggara }}</td></tr>
            <tr><th>Tahun</th><td>{{ $ptk->tahun }}</td></tr>
            <tr><th>Skor</th><td>{{ $ptk->skor }}</td></tr>
            <tr><th>Nomor Peserta</th><td>{{ $ptk->nomor_peserta }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1">Pendidikan</div>
        <table class="table table-bordered">
            <tr><th>Bidang Studi</th><td>{{ $ptk->bidang_studi }}</td></tr>
            <tr><th>Jenjang Pendidikan</th><td>{{ $ptk->jenjang_pendidikan }}</td></tr>
            <tr><th>Gelar Akademik</th><td>{{ $ptk->gelar_akademik }}</td></tr>
            <tr><th>Satuan Pendidikan Formal</th><td>{{ $ptk->satuan_pendidikan_formal }}</td></tr>
            <tr><th>Fakultas</th><td>{{ $ptk->fakultas }}</td></tr>
            <tr><th>Kependidikan</th><td>{{ $ptk->kependidikan }}</td></tr>
            <tr><th>Tahun Masuk</th><td>{{ $ptk->tahun_masuk }}</td></tr>
            <tr><th>Tahun Lulus</th><td>{{ $ptk->tahun_lulus }}</td></tr>
            <tr><th>Nomor Induk</th><td>{{ $ptk->nomor_induk }}</td></tr>
            <tr><th>Masih Studi</th><td>{{ $ptk->masih_studi }}</td></tr>
            <tr><th>Semester</th><td>{{ $ptk->semester }}</td></tr>
            <tr><th>Rata Rata Ujian</th><td>{{ $ptk->rata_rata_ujian }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1">Sertifikat</div>
        <table class="table table-bordered">
            <tr><th>Jenis Sertifikat</th><td>{{ $ptk->jenis_sertifikasi }}</td></tr>
            <tr><th>Nomor Sertifikat</th><td>{{ $ptk->nomor_sertifikat }}</td></tr>
            <tr><th>Tahun Sertifikat</th><td>{{ $ptk->tahun_sertifikasi }}</td></tr>
            <tr><th>Bidang Studi</th><td>{{ $ptk->bidang_studi }}</td></tr>
            <tr><th>Nrg</th><td>{{ $ptk->nrg }}</td></tr>
            <tr><th>Nomor Peserta</th><td>{{ $ptk->nomor_peserta }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1">Beasiswa</div>
        <table class="table table-bordered">
            <tr><th>Jenis Beasiswa</th><td>{{ $ptk->jenis_beasiswa }}</td></tr>
            <tr><th>Keterangan</th><td>{{ $ptk->keterangan }}</td></tr>
            <tr><th>Tahun Mulai</th><td>{{ $ptk->tahun_mulai }}</td></tr>
            <tr><th>Tahun AKhir</th><td>{{ $ptk->tahun_akhir }}</td></tr>
            <tr><th>Masih Menerima</th><td>{{ $ptk->masih_menerima }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1">Penghargaan</div>
        <table class="table table-bordered">
            <tr><th>Tingkat Penghargaan</th><td>{{ $ptk->tingkat_penghargaan }}</td></tr>
            <tr><th>Jenis Penghargaan</th><td>{{ $ptk->jenis_penghargaan }}</td></tr>
            <tr><th>Nama Penghargaan</th><td>{{ $ptk->nama_penghargaan }}</td></tr>
            <tr><th>Tahun</th><td>{{ $ptk->tahun }}</td></tr>
            <tr><th>Instansi</th><td>{{ $ptk->instansi }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1">Kompetensi</div>
        <table class="table table-bordered">
            <tr><th>Bidang Studi</th><td>{{ $ptk->bidang_studi }}</td></tr>
            <tr><th>Urutan</th><td>{{ $ptk->urutan }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1">Kompetensi Khusus</div>
        <table class="table table-bordered">
            <tr><th>Punya Lisensi Kepala Sekolah</th><td>{{ $ptk->punya_lisensi_kepala_sekolah }}</td></tr>
            <tr><th>Nomor Unik Kepala Sekolah</th><td>{{ $ptk->nomor_unik_kepala_sekolah }}</td></tr>
            <tr><th>Keahlian Laboraturium</th><td>{{ $ptk->keahlian_lab_oratorium }}</td></tr>
            <tr><th>Mampu Menangani</th><td>{{ $ptk->mampu_menangani }}</td></tr>
            <tr><th>Keahlian Braile</th><td>{{ $ptk->keahlian_braile }}</td></tr>
            <tr><th>Keahlian Bahasa Isyarat</th><td>{{ $ptk->keahlian_bahasa_isyarat }}</td></tr>
        </table>
    </div>

    @else
        <div class="alert alert-warning text-center">Data PTK tidak ditemukan.</div>
    @endif

</div>
@endsection
