@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Dokumen Siswa</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('siswa.dokumen-siswa.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">

                <div class="mb-3">
                    <label>Nama Siswa</label>
                    <input type="text" class="form-control" value="{{ $siswa->nama_lengkap ?? '-' }}" readonly>
                    <input type="hidden" name="peserta_didik_id" value="{{ $pesertaDidikId }}">
                </div>

                <div class="mb-3">
                    <label>Akte Kelahiran</label>
                    <input type="file" name="akte_kelahiran" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                    @error('akte_kelahiran')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Kartu Keluarga</label>
                    <input type="file" name="kartu_keluarga" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                    @error('kartu_keluarga')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>KTP Ayah</label>
                    <input type="file" name="ktp_ayah" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                    @error('ktp_ayah')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

            </div>
            <div class="col-md-6">

                <div class="mb-3">
                    <label>KTP Ibu</label>
                    <input type="file" name="ktp_ibu" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                    @error('ktp_ibu')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Ijazah SD</label>
                    <input type="file" name="ijazah_sd" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                    @error('ijazah_sd')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Ijazah SMP</label>
                    <input type="file" name="ijazah_smp" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                    @error('ijazah_smp')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('siswa.dokumen-siswa.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection
