<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Lengkap PTK</title>

    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #000; }
        h2 { text-align: center; margin-bottom: 15px; }

        .section-title {
            background: #007bff;
            color: white;
            font-weight: bold;
            padding: 4px 6px;
            font-size: 12px;
            margin-top: 12px;
        }

        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        th, td { border: 1px solid #000; padding: 5px 6px; text-align: left; vertical-align: top; }
        th { width: 35%; font-weight: bold; }

        img { max-width: 180px; max-height: 180px; }

        .text-muted { color: #777; font-style: italic; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

<h2>DATA LENGKAP PTK</h2>

@if($ptk)

    <div class="section-title">Data Pribadi</div>
    <table>
        <tr><th>Nama Lengkap</th><td>{{ $ptk->nama_lengkap }}</td></tr>
        <tr><th>NIK</th><td>{{ $ptk->nik }}</td></tr>
        <tr><th>Jenis Kelamin</th><td>{{ $ptk->jenis_kelamin }}</td></tr>
        <tr><th>Tempat, Tanggal Lahir</th><td>{{ $ptk->tempat_lahir }}, {{ $ptk->tanggal_lahir }}</td></tr>
        <tr><th>Nama Ibu Kandung</th><td>{{ $ptk->nama_ibu_kandung }}</td></tr>
        <tr><th>Agama</th><td>{{ $ptk->agama }}</td></tr>
        <tr><th>NPWP</th><td>{{ $ptk->npwp }}</td></tr>
        <tr><th>Nama Wajib Pajak</th><td>{{ $ptk->nama_wajib_pajak }}</td></tr>
        <tr><th>Kewarganegaraan</th><td>{{ $ptk->kewarganegaraan }}</td></tr>
        <tr><th>Negara Asal</th><td>{{ $ptk->negara_asal }}</td></tr>
    </table>

    <div class="section-title">Akun PTK</div>
    <table>
        <tr><th>Email</th><td>{{ $ptk->akun_email }}</td></tr>
        <tr><th>Password (hash)</th><td>{{ $ptk->akun_password }}</td></tr>
    </table>

    <div class="section-title">Dokumen PTK</div>
    <table>
        @php
            $dokumen = [
                'Akte Kelahiran' => $ptk->akte_kelahiran,
                'Kartu Keluarga' => $ptk->kartu_keluarga,
                'KTP'            => $ptk->ktp,
                'Ijazah SD'      => $ptk->ijazah_sd,
                'Ijazah SMP'     => $ptk->ijazah_smp,
                'Ijazah SMA'     => $ptk->ijazah_sma,
                'Ijazah S1'      => $ptk->ijazah_s1,
                'Ijazah S2'      => $ptk->ijazah_s2,
                'Ijazah S3'      => $ptk->ijazah_s3,
            ];
        @endphp

        @foreach($dokumen as $label => $file)
        <tr>
            <th>{{ $label }}</th>
            <td>
                @if($file)
                    <img src="{{ public_path('storage/' . $file) }}" alt="{{ $label }}">
                @else
                    <span class="text-muted">Belum diunggah</span>
                @endif
            </td>
        </tr>
        @endforeach
    </table>

    <div class="section-title">Data Keluarga</div>
    <table>
        <tr><th>No KK</th><td>{{ $ptk->no_kk }}</td></tr>
        <tr><th>Status Perkawinan</th><td>{{ $ptk->status_perkawinan }}</td></tr>
        <tr><th>Nama Suami/Istri</th><td>{{ $ptk->nama_suami_istri }}</td></tr>
        <tr><th>NIP Suami/Istri</th><td>{{ $ptk->nip_suami_istri }}</td></tr>
        <tr><th>Pekerjaan Suami/Istri</th><td>{{ $ptk->pekerjaan_suami_istri }}</td></tr>
    </table>

    <div class="section-title">Data Tunjangan</div>
    <table>
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

    <div class="section-title">Data Kesejahteraan PTK</div>
    <table>
        <tr><th>Jenis</th><td>{{ $ptk->jenis_kesejahteraan }}</td></tr>
        <tr><th>Nama</th><td>{{ $ptk->nama }}</td></tr>
        <tr><th>Penyelenggara</th><td>{{ $ptk->penyelenggara }}</td></tr>
        <tr><th>Dari Tahun</th><td>{{ $ptk->dari_tahun }}</td></tr>
        <tr><th>Sampai Tahun</th><td>{{ $ptk->sampai_tahun }}</td></tr>
        <tr><th>Status</th><td>{{ $ptk->status }}</td></tr>
    </table>

    <div class="section-title">Penugasan PTK</div>
    <table>
        <tr><th>Nomor Surat Tugas</th><td>{{ $ptk->nomor_surat_tugas }}</td></tr>
        <tr><th>Tanggal Surat Tugas</th><td>{{ $ptk->tanggal_surat_tugas }}</td></tr>
        <tr><th>TMT Tugas</th><td>{{ $ptk->tmt_tugas }}</td></tr>
        <tr><th>Status Sekolah Induk</th><td>{{ $ptk->status_sekolah_induk }}</td></tr>
    </table>

    <div class="section-title">Kepegawaian</div>
    <table>
        <tr><th>Status Kepegawaian</th><td>{{ $ptk->status_kepegawaian }}</td></tr>
        <tr><th>NIP</th><td>{{ $ptk->nip }}</td></tr>
        <tr><th>NIY/NIGK</th><td>{{ $ptk->niy_nigk }}</td></tr>
        <tr><th>NUPTK</th><td>{{ $ptk->nuptk }}</td></tr>
        <tr><th>Jenis PTK</th><td>{{ $ptk->jenis_ptk }}</td></tr>
        <tr><th>SK Pengangkatan</th><td>{{ $ptk->sk_pengangkatan }}</td></tr>
        <tr><th>TMT Pengangkatan</th><td>{{ $ptk->tmt_pengangkatan }}</td></tr>
        <tr><th>Lembaga Pengangkat</th><td>{{ $ptk->lembaga_pengangkat }}</td></tr>
        <tr><th>SK CPNS</th><td>{{ $ptk->sk_cpns }}</td></tr>
        <tr><th>TMT PNS</th><td>{{ $ptk->tmt_pns }}</td></tr>
        <tr><th>Pangkat/Golongan</th><td>{{ $ptk->pangkat_golongan }}</td></tr>
        <tr><th>Sumber Gaji</th><td>{{ $ptk->sumber_gaji }}</td></tr>
        <tr><th>Kartu Pegawai</th><td>{{ $ptk->kartu_pegawai }}</td></tr>
        <tr><th>Kartu Keluarga</th><td>{{ $ptk->kartu_keluarga }}</td></tr>
    </table>

    <div class="section-title">Tugas Tambahan</div>
    <table>
        <tr><th>Jabatan PTK</th><td>{{ $ptk->jabatan_ptk }}</td></tr>
        <tr><th>Prasarana</th><td>{{ $ptk->prasarana }}</td></tr>
        <tr><th>Nomor SK</th><td>{{ $ptk->nomor_sk }}</td></tr>
        <tr><th>TMT Tambahan</th><td>{{ $ptk->tmt_tambahan }}</td></tr>
        <tr><th>TST Tambahan</th><td>{{ $ptk->tst_tambahan }}</td></tr>
    </table>

    <div class="section-title">Riwayat Gaji</div>
    <table>
        <tr><th>Pangkat/Golongan</th><td>{{ $ptk->pangkat_golongan }}</td></tr>
        <tr><th>Nomor SK</th><td>{{ $ptk->nomor_sk }}</td></tr>
        <tr><th>Tanggal SK</th><td>{{ $ptk->tanggal_sk }}</td></tr>
        <tr><th>TMT KGB</th><td>{{ $ptk->tmt_kgb }}</td></tr>
        <tr><th>Masa Kerja</th><td>{{ $ptk->masa_kerja_thn }} tahun, {{ $ptk->masa_kerja_bln }} bulan</td></tr>
        <tr><th>Gaji Pokok</th><td>{{ $ptk->gaji_pokok }}</td></tr>
    </table>

    <div class="section-title">Riwayat Karir</div>
    <table>
        <tr><th>Jenjang Pendidikan</th><td>{{ $ptk->jenjang_pendidikan }}</td></tr>
        <tr><th>Jenis Lembaga</th><td>{{ $ptk->jenis_lembaga }}</td></tr>
        <tr><th>Status Kepegawaian</th><td>{{ $ptk->status_kepegawaian }}</td></tr>
        <tr><th>Jenis PTK</th><td>{{ $ptk->jenis_ptk }}</td></tr>
        <tr><th>Lembaga Pengangkat</th><td>{{ $ptk->lembaga_pengangkat }}</td></tr>
        <tr><th>No SK Kerja</th><td>{{ $ptk->no_sk_kerja }}</td></tr>
        <tr><th>Tgl SK Kerja</th><td>{{ $ptk->tgl_sk_kerja }}</td></tr>
        <tr><th>TMT Kerja</th><td>{{ $ptk->tmt_kerja }}</td></tr>
        <tr><th>TST Kerja</th><td>{{ $ptk->tst_kerja }}</td></tr>
        <tr><th>Tempat Kerja</th><td>{{ $ptk->tempat_kerja }}</td></tr>
        <tr><th>TTD SK</th><td>{{ $ptk->ttd_sk_kerja }}</td></tr>
    </table>

    <div class="section-title">Riwayat Jabatan</div>
    <table>
        <tr><th>Jabatan PTK</th><td>{{ $ptk->jabatan_ptk }}</td></tr>
        <tr><th>SK Jabatan</th><td>{{ $ptk->sk_jabatan }}</td></tr>
        <tr><th>TMT Jabatan</th><td>{{ $ptk->tmt_jabatan }}</td></tr>
    </table>

    <div class="section-title">Jabatan Fungsional</div>
    <table>
        <tr><th>Jabatan Fungsional</th><td>{{ $ptk->jabatan_fungsional }}</td></tr>
        <tr><th>SK Jabfung</th><td>{{ $ptk->sk_jabfung }}</td></tr>
        <tr><th>TMT Jabatan</th><td>{{ $ptk->tmt_jabatan }}</td></tr>
    </table>

    <div class="section-title">Riwayat Diklat</div>
    <table>
        <tr><th>Jenis Diklat</th><td>{{ $ptk->jenis_diklat }}</td></tr>
        <tr><th>Nama Diklat</th><td>{{ $ptk->nama_diklat }}</td></tr>
        <tr><th>No Sertifikat</th><td>{{ $ptk->no_sertifikat }}</td></tr>
        <tr><th>Penyelenggara</th><td>{{ $ptk->penyelenggara }}</td></tr>
        <tr><th>Tahun</th><td>{{ $ptk->tahun }}</td></tr>
        <tr><th>Peran</th><td>{{ $ptk->peran }}</td></tr>
        <tr><th>Tingkat</th><td>{{ $ptk->tingkat }}</td></tr>
    </table>

    <div class="section-title">Nilai Test</div>
    <table>
        <tr><th>Jenis Test</th><td>{{ $ptk->jenis_test }}</td></tr>
        <tr><th>Nama Test</th><td>{{ $ptk->nama_test }}</td></tr>
        <tr><th>Penyelenggara</th><td>{{ $ptk->penyelenggara }}</td></tr>
        <tr><th>Tahun</th><td>{{ $ptk->tahun }}</td></tr>
        <tr><th>Skor</th><td>{{ $ptk->skor }}</td></tr>
        <tr><th>Nomor Peserta</th><td>{{ $ptk->nomor_peserta }}</td></tr>
    </table>

    <div class="section-title">Pendidikan PTK</div>
    <table>
        <tr><th>Bidang Studi</th><td>{{ $ptk->bidang_studi }}</td></tr>
        <tr><th>Jenjang</th><td>{{ $ptk->jenjang_pendidikan }}</td></tr>
        <tr><th>Gelar Akademik</th><td>{{ $ptk->gelar_akademik }}</td></tr>
        <tr><th>Satuan Pendidikan</th><td>{{ $ptk->satuan_pendidikan_formal }}</td></tr>
        <tr><th>Fakultas</th><td>{{ $ptk->fakultas }}</td></tr>
        <tr><th>Kependidikan</th><td>{{ $ptk->kependidikan }}</td></tr>
        <tr><th>Tahun Masuk</th><td>{{ $ptk->tahun_masuk }}</td></tr>
        <tr><th>Tahun Lulus</th><td>{{ $ptk->tahun_lulus }}</td></tr>
        <tr><th>Nomor Induk</th><td>{{ $ptk->nomor_induk }}</td></tr>
        <tr><th>Masih Studi</th><td>{{ $ptk->masih_studi }}</td></tr>
        <tr><th>Semester</th><td>{{ $ptk->semester }}</td></tr>
        <tr><th>Rata-Rata Ujian</th><td>{{ $ptk->rata_rata_ujian }}</td></tr>
    </table>

    <div class="section-title">Sertifikasi</div>
    <table>
        <tr><th>Jenis Sertifikasi</th><td>{{ $ptk->jenis_sertifikasi }}</td></tr>
        <tr><th>Nomor Sertifikat</th><td>{{ $ptk->nomor_sertifikat }}</td></tr>
        <tr><th>Tahun Sertifikasi</th><td>{{ $ptk->tahun_sertifikasi }}</td></tr>
        <tr><th>Bidang Studi</th><td>{{ $ptk->bidang_studi }}</td></tr>
        <tr><th>NRG</th><td>{{ $ptk->nrg }}</td></tr>
        <tr><th>Nomor Peserta</th><td>{{ $ptk->nomor_peserta }}</td></tr>
    </table>

    <div class="section-title">Beasiswa PTK</div>
    <table>
        <tr><th>Jenis Beasiswa</th><td>{{ $ptk->jenis_beasiswa }}</td></tr>
        <tr><th>Keterangan</th><td>{{ $ptk->keterangan }}</td></tr>
        <tr><th>Tahun Mulai</th><td>{{ $ptk->tahun_mulai }}</td></tr>
        <tr><th>Tahun Akhir</th><td>{{ $ptk->tahun_akhir }}</td></tr>
        <tr><th>Masih Menerima</th><td>{{ $ptk->masih_menerima }}</td></tr>
    </table>

    <div class="section-title">Penghargaan</div>
    <table>
        <tr><th>Tingkat</th><td>{{ $ptk->tingkat_penghargaan }}</td></tr>
        <tr><th>Jenis Penghargaan</th><td>{{ $ptk->jenis_penghargaan }}</td></tr>
        <tr><th>Nama Penghargaan</th><td>{{ $ptk->nama_penghargaan }}</td></tr>
        <tr><th>Tahun</th><td>{{ $ptk->tahun }}</td></tr>
        <tr><th>Instansi</th><td>{{ $ptk->instansi }}</td></tr>
    </table>

    <div class="section-title">Kompetensi</div>
    <table>
        <tr><th>Bidang Studi</th><td>{{ $ptk->bidang_studi }}</td></tr>
        <tr><th>Urutan</th><td>{{ $ptk->urutan }}</td></tr>
    </table>

    <div class="section-title">Kompetensi Khusus</div>
    <table>
        <tr><th>Lisensi Kepala Sekolah</th><td>{{ $ptk->punya_lisensi_kepala_sekolah }}</td></tr>
        <tr><th>Nomor Unik Kepala Sekolah</th><td>{{ $ptk->nomor_unik_kepala_sekolah }}</td></tr>
        <tr><th>Keahlian Lab/Oratorium</th><td>{{ $ptk->keahlian_lab_oratorium }}</td></tr>
        <tr><th>Mampu Menangani</th><td>{{ $ptk->mampu_menangani }}</td></tr>
        <tr><th>Keahlian Braile</th><td>{{ $ptk->keahlian_braile }}</td></tr>
        <tr><th>Bahasa Isyarat</th><td>{{ $ptk->keahlian_bahasa_isyarat }}</td></tr>
    </table>

@else
    <p class="text-center text-muted">Data PTK tidak ditemukan.</p>
@endif

</body>
</html>
