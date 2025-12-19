@extends('layouts.app')

@section('content')

<style>
    body, html {
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
                    <select name="kewarganegaraan" id="kewarganegaraan" class="form-control">
                        <option value="">Pilih Kewarganegaraan</option>
                        <option value="WNI">WNI</option>
                        <option value="WNA">WNA</option>
                    </select>
                </div>

                <div class="mb-3" id="negaraAsalWrapper" style="display: none;">
                    <label>Negara Asal</label>
                    <input type="text" name="negara_asal" id="negara_asal" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Berkebutuhan Khusus</label>
                    <select name="berkebutuhan_khusus" class="form-control">
                        <option value="">Pilih</option>
                        <option value="Tidak">Tidak</option>
                        <option value="Netra (A)">Netra (A)</option>
                        <option value="Rungu (B)">Rungu (B)</option>
                        <option value="Grahita Ringan (C)">Grahita Ringan (C)</option>
                        <option value="Grahita Sedang (C1)">Grahita Sedang (C1)</option>
                        <option value="Daksa Ringan (D)">Daksa Ringan (D)</option>
                        <option value="Daksa Sedang (D1)">Daksa Sedang (D1)</option>
                        <option value="Wicara (F)">Wicara (F)</option>
                        <option value="Tuna Ganda (G)">Tuna Ganda (G)</option>
                        <option value="Hiper Aktif (H)">Hiper Aktif (H)</option>
                        <option value="Cerdas Istimewa (I)">Cerdas Istimewa (I)</option>
                        <option value="Bakat Istimewa (J)">Bakat Istimewa (J)</option>
                        <option value="Kesulitan Belajar (K)">Kesulitan Belajar (K)</option>
                        <option value="Narkoba (N)">Narkoba (N)</option>
                        <option value="Indigo (O)">Indigo (O)</option>
                        <option value="Down Syndrome (P)">Down Syndrome (P)</option>
                        <option value="Autis (Q)">Autis (Q)</option>
                    </select>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix.'siswa.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const kewarganegaraan = document.getElementById('kewarganegaraan');
        const negaraAsalWrapper = document.getElementById('negaraAsalWrapper');
        const negaraAsalInput = document.getElementById('negara_asal');

        function toggleNegaraAsal() {
            if (kewarganegaraan.value === 'WNA') {
                negaraAsalWrapper.style.display = 'block';
                negaraAsalInput.required = true;
            } else {
                negaraAsalWrapper.style.display = 'none';
                negaraAsalInput.required = false;
                negaraAsalInput.value = '';
            }
        }

        kewarganegaraan.addEventListener('change', toggleNegaraAsal);
        toggleNegaraAsal();
    });
</script>

@endsection
