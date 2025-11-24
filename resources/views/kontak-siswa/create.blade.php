@extends('layouts.app')

@section('content')

@php
    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    $isSiswa = $user->role === 'siswa';
    $prefix = $isAdmin ? 'admin.' : 'siswa.';
    $isEdit = isset($kontakSiswa);
@endphp

<div class="container">
    <h1 class="mb-4">{{ $isEdit ? 'Edit Kontak Siswa' : 'Tambah Kontak Siswa' }}</h1>

    <form action="{{ $isEdit ? route($prefix.'kontak-siswa.update', $kontakSiswa->id) : route($prefix.'kontak-siswa.store') }}" method="POST">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="mb-4">
            <label class="form-label fw-bold">Peserta Didik</label>
            @if($isAdmin)
                <select name="peserta_didik_id" class="form-control" required>
                    <option value="">Pilih Siswa</option>
                    @foreach($siswa as $s)
                        <option value="{{ $s->id }}" {{ old('peserta_didik_id', $kontakSiswa->peserta_didik_id ?? '') == $s->id ? 'selected' : '' }}>
                            {{ $s->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
            @elseif($isSiswa)
                <input type="hidden" name="peserta_didik_id" value="{{ $siswa->id }}">
                <input type="text" class="form-control" value="{{ $siswa->nama_lengkap }}" disabled>
            @endif
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3"><label>No HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $kontakSiswa->no_hp ?? '') }}">
                </div>

                <div class="mb-3"><label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $kontakSiswa->email ?? '') }}">
                </div>

                <div class="mb-3"><label>Alamat Jalan</label>
                    <input type="text" name="alamat_jalan" class="form-control" value="{{ old('alamat_jalan', $kontakSiswa->alamat_jalan ?? '') }}">
                </div>

                <div class="mb-3"><label>RT</label>
                    <input type="text" name="rt" class="form-control" value="{{ old('rt', $kontakSiswa->rt ?? '') }}">
                </div>

                <div class="mb-3"><label>RW</label>
                    <input type="text" name="rw" class="form-control" value="{{ old('rw', $kontakSiswa->rw ?? '') }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3"><label>Kelurahan</label>
                    <input type="text" name="kelurahan" class="form-control" value="{{ old('kelurahan', $kontakSiswa->kelurahan ?? '') }}">
                </div>

                <div class="mb-3"><label>Kecamatan</label>
                    <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan', $kontakSiswa->kecamatan ?? '') }}">
                </div>

                <div class="mb-3"><label>Kode Pos</label>
                    <input type="text" name="kode_pos" class="form-control" value="{{ old('kode_pos', $kontakSiswa->kode_pos ?? '') }}">
                </div>

                <div class="mb-3">
                    <label>Tempat Tinggal</label>
                    <select name="tempat_tinggal" class="form-control">
                        <option value="">Pilih Tempat Tinggal</option>
                        @foreach(['Bersama Orang Tua','Kos','Asrama','Lainnya'] as $option)
                            <option value="{{ $option }}" {{ old('tempat_tinggal', $kontakSiswa->tempat_tinggal ?? '') == $option ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3"><label>Moda Transportasi</label>
                    <input type="text" name="moda_transportasi" class="form-control" value="{{ old('moda_transportasi', $kontakSiswa->moda_transportasi ?? '') }}">
                </div>

                <div class="mb-3"><label>Anak Ke</label>
                    <input type="number" name="anak_ke" class="form-control" value="{{ old('anak_ke', $kontakSiswa->anak_ke ?? '') }}">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <a href="{{ route($prefix.'kontak-siswa.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">{{ $isEdit ? 'Perbarui' : 'Simpan' }}</button>
        </div>
    </form>
</div>

@endsection
