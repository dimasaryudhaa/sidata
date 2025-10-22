@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Registrasi Siswa</h1>

    <form action="{{ route('registrasi-siswa.store') }}" method="POST">
        @csrf

        <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama Siswa</label>
                    <select name="peserta_didik_id" class="form-control" required>
                        <option value="">Pilih Siswa</option>
                        @foreach($siswa as $s)
                            <option value="{{ $s->id }}">{{ $s->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Jenis Pendaftaran</label>
                    <select name="jenis_pendaftaran" class="form-control" required>
                        <option value="">Pilih Jenis Pendaftaran</option>
                        <option value="Siswa Baru">Siswa Baru</option>
                        <option value="Pindahan">Pindahan</option>
                        <option value="Kembali Bersekolah">Kembali Bersekolah</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Sekolah Asal</label>
                    <input type="text" name="sekolah_asal" class="form-control" placeholder="Nama sekolah sebelumnya">
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label>No Peserta UN</label>
                    <input type="text" name="no_peserta_un" class="form-control">
                </div>

                <div class="mb-3">
                    <label>No Seri Ijazah</label>
                    <input type="text" name="no_seri_ijazah" class="form-control">
                </div>

                <div class="mb-3">
                    <label>No SKHUN</label>
                    <input type="text" name="no_skhun" class="form-control">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <a href="{{ route('registrasi-siswa.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection
