@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Beasiswa Siswa</h1>
    <form action="{{ route('beasiswa.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama Siswa</label>
                    <input type="text" class="form-control" value="{{ $siswa->nama_lengkap ?? '-' }}" readonly>
                    <input type="hidden" name="peserta_didik_id" value="{{ $siswaId }}">
                </div>

                <div class="mb-3">
                    <label>Jenis Beasiswa</label>
                    <select name="jenis_beasiswa" class="form-control" required>
                        <option value="">Pilih Jenis</option>
                        <option value="Anak Berprestasi">Anak Berprestasi</option>
                        <option value="Anak Miskin">Anak Miskin</option>
                        <option value="Pendidikan">Pendidikan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Keterangan</label>
                    <input type="text" name="keterangan" class="form-control">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Tahun Mulai</label>
                    <input type="number" name="tahun_mulai" class="form-control" placeholder="YYYY">
                </div>
                <div class="mb-3">
                    <label>Tahun Selesai</label>
                    <input type="number" name="tahun_selesai" class="form-control" placeholder="YYYY">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('beasiswa.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection
