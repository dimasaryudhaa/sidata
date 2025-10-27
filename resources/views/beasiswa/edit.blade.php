@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Beasiswa Siswa</h1>
        <form action="{{ $beasiswa->id ? route('beasiswa.update', $beasiswa->id) : route('beasiswa.store') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama Siswa</label>
                    <input type="text" class="form-control" value="{{ $beasiswa->siswa->nama_lengkap ?? '-' }}" readonly>
                    <input type="hidden" name="peserta_didik_id" value="{{ $beasiswa->peserta_didik_id }}">
                </div>

                <div class="mb-3">
                    <label>Jenis Beasiswa</label>
                    <select name="jenis_beasiswa" class="form-control" required>
                        <option value="">Pilih Jenis</option>
                        <option value="Anak Berprestasi" {{ $beasiswa->jenis_beasiswa == 'Anak Berprestasi' ? 'selected' : '' }}>Anak Berprestasi</option>
                        <option value="Anak Miskin" {{ $beasiswa->jenis_beasiswa == 'Anak Miskin' ? 'selected' : '' }}>Anak Miskin</option>
                        <option value="Pendidikan" {{ $beasiswa->jenis_beasiswa == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" value="{{ $beasiswa->keterangan }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Tahun Mulai</label>
                    <input type="number" name="tahun_mulai" class="form-control" placeholder="YYYY" value="{{ $beasiswa->tahun_mulai }}">
                </div>

                <div class="mb-3">
                    <label>Tahun Selesai</label>
                    <input type="number" name="tahun_selesai" class="form-control" placeholder="YYYY" value="{{ $beasiswa->tahun_selesai }}">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('beasiswa.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Update</button>
        </div>
    </form>
</div>
@endsection
