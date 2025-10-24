@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ $data->id ? 'Edit Akun Siswa' : 'Tambah Akun Siswa' }}</h1>

    <form action="{{ $data->id ? route('akun-siswa.update', $data->id) : route('akun-siswa.store') }}" method="POST">
        @csrf
        @if($data->id)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama Siswa</label>
                    <input type="text" class="form-control" value="{{ $data->siswa->nama_lengkap ?? '' }}" readonly>
                    <input type="hidden" name="peserta_didik_id" value="{{ $data->peserta_didik_id }}">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $data->email }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Password Baru</label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin diubah">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('akun-siswa.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">
                {{ $data->id ? 'Perbarui' : 'Simpan' }}
            </button>
        </div>
    </form>
</div>
@endsection
