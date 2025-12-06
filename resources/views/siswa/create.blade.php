@extends('layouts.app')

@section('content')

<style>
    body {
        overflow: hidden;
    }
    html {
        overflow: hidden;
    }
</style>

<div class="container">
    <h1 class="mb-4">Tambah Peserta Didik</h1>

    <form action="{{ route($prefix.'siswa.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>NIS</label>
                    <input type="text" name="nis" class="form-control">
                </div>

                <div class="mb-3">
                    <label>NISN</label>
                    <input type="text" name="nisn" class="form-control">
                </div>

                <div class="mb-3">
                    <label>NIK</label>
                    <input type="text" name="nik" class="form-control">
                </div>

                <div class="mb-3">
                    <label>No KK</label>
                    <input type="text" name="no_kk" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Rombel</label>
                    <select name="rombel_id" class="form-control">
                        <option value="">Pilih Rombel</option>
                        @foreach($rombels as $rombel)
                            <option value="{{ $rombel->id }}">{{ $rombel->nama_rombel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Agama</label>
                    <select name="agama" class="form-control">
                        <option value="">Pilih Agama</option>
                        <option value="Islam">Islam</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Buddha">Buddha</option>
                        <option value="Konghucu">Konghucu</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Rayon</label>
                    <select name="rayon_id" class="form-control">
                        <option value="">Pilih Rayon</option>
                        @foreach($rayons as $rayon)
                            <option value="{{ $rayon->id }}">{{ $rayon->nama_rayon }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Kewarganegaraan</label>
                    <select name="kewarganegaraan" class="form-control">
                        <option value="">Pilih Kewarganegaraan</option>
                        <option value="WNI">WNI</option>
                        <option value="WNA">WNA</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Negara Asal</label>
                    <input type="text" name="negara_asal" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Berkebutuhan Khusus</label>
                    <input type="text" name="berkebutuhan_khusus" class="form-control">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix.'siswa.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>

@endsection
