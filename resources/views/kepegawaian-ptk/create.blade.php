@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Data Kepegawaian PTK</h2>

    <form action="{{ route('kepegawaian-ptk.store') }}" method="POST">
        @csrf

        <div class="row">
            {{-- Kolom Kiri --}}
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <select name="ptk_id" class="form-control" required>
                        <option value="">Pilih PTK</option>
                        @foreach($ptks as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Status Kepegawaian</label>
                    <select name="status_kepegawaian" class="form-control" required>
                        <option value="">Pilih Status Kepegawaian</option>
                        <option value="PNS">PNS</option>
                        <option value="PNS Diperbantukan">PNS Diperbantukan</option>
                        <option value="PNS Depag">PNS Depag</option>
                        <option value="GTY/PTY">GTY/PTY</option>
                        <option value="GTT/PTT Propinsi">GTT/PTT Propinsi</option>
                        <option value="GTT/PTT Kab/Kota">GTT/PTT Kab/Kota</option>
                        <option value="Guru Bantu Pusat">Guru Bantu Pusat</option>
                        <option value="Guru Honor Sekolah">Guru Honor Sekolah</option>
                        <option value="Tenaga Honor">Tenaga Honor</option>
                        <option value="CPNS">CPNS</option>
                        <option value="PPPK">PPPK</option>
                        <option value="PPNPN">PPNPN</option>
                        <option value="Kontrak Kerja WNA">Kontrak Kerja WNA</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>NIP</label>
                    <input type="text" name="nip" class="form-control">
                </div>

                <div class="mb-3">
                    <label>NIY/NIGK</label>
                    <input type="text" name="niy_nigk" class="form-control">
                </div>

                <div class="mb-3">
                    <label>NUPTK</label>
                    <input type="text" name="nuptk" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Jenis PTK</label>
                    <select name="jenis_ptk" class="form-control" required>
                        <option value="">Pilih Jenis PTK</option>
                        <option value="Kepala Sekolah" {{ old('jenis_ptk', $kepegawaianPtk->jenis_ptk ?? '') == 'Kepala Sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                        <option value="Guru" {{ old('jenis_ptk', $kepegawaianPtk->jenis_ptk ?? '') == 'Guru' ? 'selected' : '' }}>Guru</option>
                        <option value="Tenaga Kependidikan" {{ old('jenis_ptk', $kepegawaianPtk->jenis_ptk ?? '') == 'Tenaga Kependidikan' ? 'selected' : '' }}>Tenaga Kependidikan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>SK Pengangkatan</label>
                    <input type="text" name="sk_pengangkatan" class="form-control">
                </div>
            </div>

            {{-- Kolom Kanan --}}
            <div class="col-md-6">
                <div class="mb-3">
                    <label>TMT Pengangkatan</label>
                    <input type="date" name="tmt_pengangkatan" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Lembaga Pengangkat</label>
                    <select name="lembaga_pengangkat" class="form-control" required>
                        <option value="">Pilih Lembaga Pengangkat</option>
                        <option value="Pemerintah Pusat">Pemerintah Pusat</option>
                        <option value="Pemerintah Provinsi">Pemerintah Provinsi</option>
                        <option value="Pemerintah Kab/Kota">Pemerintah Kab/Kota</option>
                        <option value="Ketua Yayasan">Ketua Yayasan</option>
                        <option value="Kepala Sekolah">Kepala Sekolah</option>
                        <option value="Komite Sekolah">Komite Sekolah</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>SK CPNS</label>
                    <input type="text" name="sk_cpns" class="form-control">
                </div>

                <div class="mb-3">
                    <label>TMT PNS</label>
                    <input type="date" name="tmt_pns" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Pangkat/Golongan</label>
                    <input type="text" name="pangkat_golongan" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Sumber Gaji</label>
                    <select name="sumber_gaji" class="form-control" required>
                        <option value="">Pilih Sumber Gaji</option>
                        <option value="APBN">APBN</option>
                        <option value="APBD Provinsi">APBD Provinsi</option>
                        <option value="APBD Kab/Kota">APBD Kab/Kota</option>
                        <option value="Yayasan">Yayasan</option>
                        <option value="Sekolah">Sekolah</option>
                        <option value="Lembaga Donor">Lembaga Donor</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Kartu Pegawai</label>
                    <input type="text" name="kartu_pegawai" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Kartu Keluarga</label>
                    <input type="text" name="kartu_keluarga" class="form-control">
                </div>
            </div>
        </div>

        <div class="text-end mt-3">
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('kepegawaian-ptk.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
