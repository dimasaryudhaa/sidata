@extends('layouts.app')

@section('content')

@php
    $user = Auth::user();
    $role = $user->role;
    $prefix = $role === 'admin' ? 'admin.' : ($role === 'ptk' ? 'ptk.' : 'siswa.');
    $isEdit = isset($data->id);
@endphp

<div class="container">
    <h2 class="mb-4">
        {{ $isEdit ? 'Edit Kontak Siswa' : 'Tambah Kontak Siswa' }}
    </h2>

    <form action="{{ $isEdit ? route($prefix.'kontak-siswa.update', $data->id) : route($prefix.'kontak-siswa.store') }}" method="POST">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="row">

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Peserta Didik</label>
                    <input type="text" class="form-control" value="{{ $data->siswa->nama_lengkap ?? '-' }}" readonly>
                    <input type="hidden" name="peserta_didik_id" value="{{ $data->peserta_didik_id }}">
                </div>

                <div class="mb-3">
                    <label>No HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $data->no_hp) }}">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $data->email) }}">
                </div>

                <div class="mb-3">
                    <label>Alamat Jalan</label>
                    <input type="text" name="alamat_jalan" class="form-control" value="{{ old('alamat_jalan', $data->alamat_jalan) }}">
                </div>

                <div class="mb-3">
                    <label>RT</label>
                    <input type="text" name="rt" class="form-control" value="{{ old('rt', $data->rt) }}">
                </div>

                <div class="mb-3">
                    <label>RW</label>
                    <input type="text" name="rw" class="form-control" value="{{ old('rw', $data->rw) }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Kelurahan</label>
                    <input type="text" name="kelurahan" class="form-control" value="{{ old('kelurahan', $data->kelurahan) }}">
                </div>

                <div class="mb-3">
                    <label>Kecamatan</label>
                    <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan', $data->kecamatan) }}">
                </div>

                <div class="mb-3">
                    <label>Kode Pos</label>
                    <input type="text" name="kode_pos" class="form-control" value="{{ old('kode_pos', $data->kode_pos) }}">
                </div>

                <div class="mb-3">
                    <label>Tempat Tinggal</label>
                    <select name="tempat_tinggal" class="form-control">
                        <option value="">Pilih Tempat Tinggal</option>
                        @foreach(['Bersama Orang Tua','Kos','Asrama','Lainnya'] as $option)
                            <option value="{{ $option }}" {{ old('tempat_tinggal', $data->tempat_tinggal) == $option ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Moda Transportasi</label>
                    <input type="text" name="moda_transportasi" class="form-control" value="{{ old('moda_transportasi', $data->moda_transportasi) }}">
                </div>

                <div class="mb-3">
                    <label>Anak Ke</label>
                    <input type="number" name="anak_ke" class="form-control" value="{{ old('anak_ke', $data->anak_ke) }}">
                </div>
            </div>

        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix.'kontak-siswa.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">{{ $isEdit ? 'Perbarui' : 'Simpan' }}</button>
        </div>
    </form>
</div>

@endsection
