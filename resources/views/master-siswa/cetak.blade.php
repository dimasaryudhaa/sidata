<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Lengkap Peserta Didik</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #000; }
        h2 { text-align: center; margin-bottom: 15px; }

        .section-title {
            background: #007bff;
            color: white;
            font-weight: bold;
            padding: 4px 6px;
            font-size: 12px;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            width: 35%;
            background: #f2f2f2;
        }

        img {
            max-width: 180px;
            max-height: 180px;
        }

        .text-muted {
            color: #888;
            font-style: italic;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

<h2>Data Lengkap Peserta Didik</h2>

@if($siswa)

    <div class="section-title">Data Pribadi</div>
    <table>
        <tr><th>Nama Lengkap</th><td>{{ $siswa->nama_lengkap }}</td></tr>
        <tr><th>Jenis Kelamin</th><td>{{ $siswa->jenis_kelamin }}</td></tr>
        <tr><th>NIS</th><td>{{ $siswa->nis }}</td></tr>
        <tr><th>NISN</th><td>{{ $siswa->nisn }}</td></tr>
        <tr><th>NIK</th><td>{{ $siswa->nik }}</td></tr>
        <tr><th>No KK</th><td>{{ $siswa->no_kk }}</td></tr>
        <tr><th>Tempat, Tanggal Lahir</th><td>{{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir }}</td></tr>
        <tr><th>Agama</th><td>{{ $siswa->agama }}</td></tr>
        <tr><th>Kewarganegaraan</th><td>{{ $siswa->kewarganegaraan }}</td></tr>
        <tr><th>Negara Asal</th><td>{{ $siswa->negara_asal }}</td></tr>
        <tr><th>Berkebutuhan Khusus</th><td>{{ $siswa->berkebutuhan_khusus }}</td></tr>
    </table>

    <div class="section-title">Akun Siswa</div>
    <table>
        <tr><th>Email</th><td>{{ $siswa->akun_email }}</td></tr>
        <tr><th>Password (terenkripsi)</th><td>{{ $siswa->akun_password }}</td></tr>
    </table>

    <div class="section-title">Data Periodik</div>
    <table>
        <tr><th>Tinggi Badan</th><td>{{ $siswa->tinggi_badan_cm }} cm</td></tr>
        <tr><th>Berat Badan</th><td>{{ $siswa->berat_badan_kg }} kg</td></tr>
        <tr><th>Lingkar Kepala</th><td>{{ $siswa->lingkar_kepala_cm }} cm</td></tr>
        <tr><th>Jarak ke Sekolah</th><td>{{ $siswa->jarak_ke_sekolah }}</td></tr>
        <tr><th>Jarak Sebenarnya</th><td>{{ $siswa->jarak_sebenarnya_km }} km</td></tr>
        <tr><th>Waktu Tempuh</th><td>{{ $siswa->waktu_tempuh_jam }} jam {{ $siswa->waktu_tempuh_menit }} menit</td></tr>
        <tr><th>Jumlah Saudara</th><td>{{ $siswa->jumlah_saudara }}</td></tr>
    </table>

    <div class="section-title">Data Ayah</div>
    <table>
        <tr><th>Nama Ayah</th><td>{{ $siswa->nama_ayah }}</td></tr>
        <tr><th>NIK Ayah</th><td>{{ $siswa->nik_ayah }}</td></tr>
        <tr><th>Tahun Lahir</th><td>{{ $siswa->tahun_lahir_ayah }}</td></tr>
        <tr><th>Pendidikan</th><td>{{ $siswa->pendidikan_ayah }}</td></tr>
        <tr><th>Pekerjaan</th><td>{{ $siswa->pekerjaan_ayah }}</td></tr>
        <tr><th>Penghasilan</th><td>{{ $siswa->penghasilan_ayah }}</td></tr>
        <tr><th>Berkebutuhan Khusus</th><td>{{ $siswa->kebutuhan_khusus_ayah }}</td></tr>
    </table>

    <div class="section-title">Data Ibu</div>
    <table>
        <tr><th>Nama Ibu</th><td>{{ $siswa->nama_ibu }}</td></tr>
        <tr><th>NIK Ibu</th><td>{{ $siswa->nik_ibu }}</td></tr>
        <tr><th>Tahun Lahir</th><td>{{ $siswa->tahun_lahir_ibu }}</td></tr>
        <tr><th>Pendidikan</th><td>{{ $siswa->pendidikan_ibu }}</td></tr>
        <tr><th>Pekerjaan</th><td>{{ $siswa->pekerjaan_ibu }}</td></tr>
        <tr><th>Penghasilan</th><td>{{ $siswa->penghasilan_ibu }}</td></tr>
        <tr><th>Berkebutuhan Khusus</th><td>{{ $siswa->kebutuhan_khusus_ibu }}</td></tr>
    </table>

    <div class="section-title">Data Wali</div>
    <table>
        <tr><th>Nama Wali</th><td>{{ $siswa->nama_wali }}</td></tr>
        <tr><th>NIK Wali</th><td>{{ $siswa->nik_wali }}</td></tr>
        <tr><th>Tahun Lahir</th><td>{{ $siswa->tahun_lahir_wali }}</td></tr>
        <tr><th>Pendidikan</th><td>{{ $siswa->pendidikan_wali }}</td></tr>
        <tr><th>Pekerjaan</th><td>{{ $siswa->pekerjaan_wali }}</td></tr>
        <tr><th>Penghasilan</th><td>{{ $siswa->penghasilan_wali }}</td></tr>
    </table>

    <div class="section-title">Beasiswa & Prestasi</div>
    <table>
        <tr><th>Jenis Beasiswa</th><td>{{ $siswa->jenis_beasiswa }}</td></tr>
        <tr><th>Keterangan</th><td>{{ $siswa->beasiswa_keterangan }}</td></tr>
        <tr><th>Tahun Mulai</th><td>{{ $siswa->beasiswa_tahun_mulai }}</td></tr>
        <tr><th>Tahun Selesai</th><td>{{ $siswa->beasiswa_tahun_selesai }}</td></tr>
        <tr><th>Nama Prestasi</th><td>{{ $siswa->nama_prestasi }}</td></tr>
        <tr><th>Tingkat</th><td>{{ $siswa->tingkat_prestasi }}</td></tr>
        <tr><th>Penyelenggara</th><td>{{ $siswa->penyelenggara }}</td></tr>
        <tr><th>Peringkat</th><td>{{ $siswa->peringkat }}</td></tr>
    </table>

    <div class="section-title">Data Kesejahteraan</div>
    <table>
        <tr><th>Jenis Kesejahteraan</th><td>{{ $siswa->jenis_kesejahteraan }}</td></tr>
        <tr><th>No Kartu</th><td>{{ $siswa->no_kartu }}</td></tr>
        <tr><th>Nama di Kartu</th><td>{{ $siswa->nama_di_kartu }}</td></tr>
    </table>

    <div class="section-title">Kontak & Alamat</div>
    <table>
        <tr><th>No HP</th><td>{{ $siswa->no_hp }}</td></tr>
        <tr><th>Email</th><td>{{ $siswa->kontak_email }}</td></tr>
        <tr><th>Alamat</th><td>{{ $siswa->alamat_jalan }}</td></tr>
        <tr><th>RT/RW</th><td>{{ $siswa->rt }}/{{ $siswa->rw }}</td></tr>
        <tr><th>Kelurahan</th><td>{{ $siswa->kelurahan }}</td></tr>
        <tr><th>Kecamatan</th><td>{{ $siswa->kecamatan }}</td></tr>
        <tr><th>Kode Pos</th><td>{{ $siswa->kode_pos }}</td></tr>
        <tr><th>Tempat Tinggal</th><td>{{ $siswa->tempat_tinggal }}</td></tr>
        <tr><th>Moda Transportasi</th><td>{{ $siswa->moda_transportasi }}</td></tr>
        <tr><th>Anak ke</th><td>{{ $siswa->anak_ke }}</td></tr>
    </table>

    <div class="section-title">Data Dokumen</div>
    <table>
        <tr>
            <th>Akte Kelahiran</th>
            <td>
                @if($siswa->akte_kelahiran)
                    <img src="{{ public_path('storage/' . $siswa->akte_kelahiran) }}" alt="Akte Kelahiran">
                @else
                    <span class="text-muted">Belum diunggah</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Kartu Keluarga</th>
            <td>
                @if($siswa->kartu_keluarga)
                    <img src="{{ public_path('storage/' . $siswa->kartu_keluarga) }}" alt="Kartu Keluarga">
                @else
                    <span class="text-muted">Belum diunggah</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>KTP Ayah</th>
            <td>
                @if($siswa->ktp_ayah)
                    <img src="{{ public_path('storage/' . $siswa->ktp_ayah) }}" alt="KTP Ayah">
                @else
                    <span class="text-muted">Belum diunggah</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>KTP Ibu</th>
            <td>
                @if($siswa->ktp_ibu)
                    <img src="{{ public_path('storage/' . $siswa->ktp_ibu) }}" alt="KTP Ibu">
                @else
                    <span class="text-muted">Belum diunggah</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Ijazah SD</th>
            <td>
                @if($siswa->ijazah_sd)
                    <img src="{{ public_path('storage/' . $siswa->ijazah_sd) }}" alt="Ijazah SD">
                @else
                    <span class="text-muted">Belum diunggah</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Ijazah SMP</th>
            <td>
                @if($siswa->ijazah_smp)
                    <img src="{{ public_path('storage/' . $siswa->ijazah_smp) }}" alt="Ijazah SMP">
                @else
                    <span class="text-muted">Belum diunggah</span>
                @endif
            </td>
        </tr>
    </table>

@else
    <p class="text-center text-muted">Data siswa tidak ditemukan.</p>
@endif

</body>
</html>
