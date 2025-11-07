@extends('layouts.app')

@section('content')
<style>
    .table {
        border: 1px solid #dee2e6;
        width: 100%;
    }

    .table th,
    .table td {
        padding: 8px 12px;
    }

    .table th {
        font-weight: 600;
        width: 35%;
    }

</style>

<div class="container">
    <h3 class="mb-4 fw-bold text-center">Data Lengkap Peserta Didik</h3>

    <div class="text-end mb-3">
        <a href="{{ route('master-siswa.cetak', $siswa->id) }}"
           class="btn btn-danger" target="_blank">
            <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
        </a>
    </div>
    
    @if($siswa)

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1">Data Pribadi</div>
        <table class="table table-bordered">
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
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1">Akun Siswa</div>
        <table class="table table-bordered table-sm">
            <tr><th>Email</th><td>{{ $siswa->akun_email }}</td></tr>
            <tr><th>Password (terenkripsi)</th><td>{{ $siswa->akun_password }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1">Data Periodik</div>
        <table class="table table-bordered table-sm">
            <tr><th>Tinggi Badan</th><td>{{ $siswa->tinggi_badan_cm }} cm</td></tr>
            <tr><th>Berat Badan</th><td>{{ $siswa->berat_badan_kg }} kg</td></tr>
            <tr><th>Lingkar Kepala</th><td>{{ $siswa->lingkar_kepala_cm }} cm</td></tr>
            <tr><th>Jarak ke Sekolah</th><td>{{ $siswa->jarak_ke_sekolah }}</td></tr>
            <tr><th>Jarak Sebenarnya</th><td>{{ $siswa->jarak_sebenarnya_km }} km</td></tr>
            <tr><th>Waktu Tempuh</th><td>{{ $siswa->waktu_tempuh_jam }} jam {{ $siswa->waktu_tempuh_menit }} menit</td></tr>
            <tr><th>Jumlah Saudara</th><td>{{ $siswa->jumlah_saudara }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="card-header bg-primary text-white fw-bold fs-5 ps-2 py-1">Data Ayah</div>
        <table class="table table-bordered table-sm">
            <tr><th>Nama Ayah</th><td>{{ $siswa->nama_ayah }}</td></tr>
            <tr><th>NIK Ayah</th><td>{{ $siswa->nik_ayah }}</td></tr>
            <tr><th>Tahun Lahir</th><td>{{ $siswa->tahun_lahir_ayah }}</td></tr>
            <tr><th>Pendidikan</th><td>{{ $siswa->pendidikan_ayah }}</td></tr>
            <tr><th>Pekerjaan</th><td>{{ $siswa->pekerjaan_ayah }}</td></tr>
            <tr><th>Penghasilan</th><td>{{ $siswa->penghasilan_ayah }}</td></tr>
            <tr><th>Berkebutuhan Khusus</th><td>{{ $siswa->kebutuhan_khusus_ayah }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="card-header bg-primary text-white fw-bold fs-5 ps-2 py-1">Data Ibu</div>
        <div class="card-body">
            <table class="table table-bordered table-sm">
                <tr><th>Nama Ibu</th><td>{{ $siswa->nama_ibu }}</td></tr>
                <tr><th>NIK Ibu</th><td>{{ $siswa->nik_ibu }}</td></tr>
                <tr><th>Tahun Lahir</th><td>{{ $siswa->tahun_lahir_ibu }}</td></tr>
                <tr><th>Pendidikan</th><td>{{ $siswa->pendidikan_ibu }}</td></tr>
                <tr><th>Pekerjaan</th><td>{{ $siswa->pekerjaan_ibu }}</td></tr>
                <tr><th>Penghasilan</th><td>{{ $siswa->penghasilan_ibu }}</td></tr>
                <tr><th>Berkebutuhan Khusus</th><td>{{ $siswa->kebutuhan_khusus_ibu }}</td></tr>
            </table>
        </div>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="card-header bg-primary text-white fw-bold fs-5 ps-2 py-1">Data Wali</div>
        <div class="card-body">
            <table class="table table-bordered table-sm">
                <tr><th>Nama Wali</th><td>{{ $siswa->nama_wali }}</td></tr>
                <tr><th>NIK Wali</th><td>{{ $siswa->nik_wali }}</td></tr>
                <tr><th>Tahun Lahir</th><td>{{ $siswa->tahun_lahir_wali }}</td></tr>
                <tr><th>Pendidikan</th><td>{{ $siswa->pendidikan_wali }}</td></tr>
                <tr><th>Pekerjaan</th><td>{{ $siswa->pekerjaan_wali }}</td></tr>
                <tr><th>Penghasilan</th><td>{{ $siswa->penghasilan_wali }}</td></tr>
            </table>
        </div>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1">Beasiswa & Prestasi</div>
        <table class="table table-bordered table-sm mb-3">
            <tr><th>Jenis Beasiswa</th><td>{{ $siswa->jenis_beasiswa }}</td></tr>
            <tr><th>Keterangan</th><td>{{ $siswa->beasiswa_keterangan }}</td></tr>
            <tr><th>Tahun Mulai</th><td>{{ $siswa->beasiswa_tahun_mulai }}</td></tr>
            <tr><th>Tahun Selesai</th><td>{{ $siswa->beasiswa_tahun_selesai }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1">Beasiswa & Prestasi</div>
        <div class="card-body">

            <table class="table table-bordered table-sm">
                <tr><th>Nama Prestasi</th><td>{{ $siswa->nama_prestasi }}</td></tr>
                <tr><th>Tingkat</th><td>{{ $siswa->tingkat_prestasi }}</td></tr>
                <tr><th>Penyelenggara</th><td>{{ $siswa->penyelenggara }}</td></tr>
                <tr><th>Peringkat</th><td>{{ $siswa->peringkat }}</td></tr>
            </table>
        </div>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1">Data Registrasi</div>
        <table class="table table-bordered table-sm">
            <tr><th>Jenis Pendaftaran</th><td>{{ $siswa->jenis_pendaftaran }}</td></tr>
            <tr><th>Tanggal Masuk</th><td>{{ $siswa->tanggal_masuk }}</td></tr>
            <tr><th>Sekolah Asal</th><td>{{ $siswa->sekolah_asal }}</td></tr>
            <tr><th>No Peserta UN</th><td>{{ $siswa->no_peserta_un }}</td></tr>
            <tr><th>No Seri Ijazah</th><td>{{ $siswa->no_seri_ijazah }}</td></tr>
            <tr><th>No SKHUN</th><td>{{ $siswa->no_skhun }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1">Data Kesejahteraan</div>
        <table class="table table-bordered table-sm">
            <tr><th>Jenis Kesejahteraan</th><td>{{ $siswa->jenis_kesejahteraan }}</td></tr>
            <tr><th>No Kartu</th><td>{{ $siswa->no_kartu }}</td></tr>
            <tr><th>Nama di Kartu</th><td>{{ $siswa->nama_di_kartu }}</td></tr>
        </table>
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1">Data Kontak & Alamat</div>
        <table class="table table-bordered table-sm">
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
    </div>

    <div class="table-responsive overflow-hidden mt-3">
        <div class="bg-primary text-white fw-bold fs-5 ps-2 py-1">Data Dokumen</div>
        <table class="table table-bordered table-sm align-middle">
            <tr>
                <th>Akte Kelahiran</th>
                <td>
                    @if($siswa->akte_kelahiran)
                        <img src="{{ asset('storage/' . $siswa->akte_kelahiran) }}" alt="Akte Kelahiran"
                             class="img-thumbnail" style="max-width: 180px; max-height: 180px;">
                    @else
                        <span class="text-muted">Belum diunggah</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Kartu Keluarga</th>
                <td>
                    @if($siswa->kartu_keluarga)
                        <img src="{{ asset('storage/' . $siswa->kartu_keluarga) }}" alt="Kartu Keluarga"
                             class="img-thumbnail" style="max-width: 180px; max-height: 180px;">
                    @else
                        <span class="text-muted">Belum diunggah</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>KTP Ayah</th>
                <td>
                    @if($siswa->ktp_ayah)
                        <img src="{{ asset('storage/' . $siswa->ktp_ayah) }}" alt="KTP Ayah"
                             class="img-thumbnail" style="max-width: 180px; max-height: 180px;">
                    @else
                        <span class="text-muted">Belum diunggah</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>KTP Ibu</th>
                <td>
                    @if($siswa->ktp_ibu)
                        <img src="{{ asset('storage/' . $siswa->ktp_ibu) }}" alt="KTP Ibu"
                             class="img-thumbnail" style="max-width: 180px; max-height: 180px;">
                    @else
                        <span class="text-muted">Belum diunggah</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Ijazah SD</th>
                <td>
                    @if($siswa->ijazah_sd)
                        <img src="{{ asset('storage/' . $siswa->ijazah_sd) }}" alt="Ijazah SD"
                             class="img-thumbnail" style="max-width: 180px; max-height: 180px;">
                    @else
                        <span class="text-muted">Belum diunggah</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Ijazah SMP</th>
                <td>
                    @if($siswa->ijazah_smp)
                        <img src="{{ asset('storage/' . $siswa->ijazah_smp) }}" alt="Ijazah SMP"
                             class="img-thumbnail" style="max-width: 180px; max-height: 180px;">
                    @else
                        <span class="text-muted">Belum diunggah</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    @else
        <div class="alert alert-warning text-center">Data siswa tidak ditemukan.</div>
    @endif
</div>
@endsection
