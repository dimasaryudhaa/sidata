@extends('layouts.app')

@section('content')

@php
    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    $isSiswa = $user->role === 'siswa';
    $prefix = $isAdmin ? 'admin.' : 'siswa.';
@endphp

<div class="container">
    <h1 class="mb-4">Tambah Registrasi Siswa</h1>

    <form action="{{ route($prefix.'registrasi-siswa.store') }}" method="POST">
        @csrf

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white fw-bold">
                Data Registrasi Siswa
            </div>

            <div class="card-body row">
                <div class="col-md-6">

                    <div class="mb-3">
                        <label class="fw-bold">Nama Siswa</label>

                        @if($isAdmin)
                            <select name="peserta_didik_id" class="form-control" required>
                                <option value="">Pilih Siswa</option>
                                @foreach($siswa as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama_lengkap }}</option>
                                @endforeach
                            </select>

                        @elseif($isSiswa)
                            <input type="hidden" name="peserta_didik_id" value="{{ $siswa->id }}">
                            <input type="text" class="form-control" value="{{ $siswa->nama_lengkap }}" disabled>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Jenis Pendaftaran</label>
                        <select name="jenis_pendaftaran" class="form-control" required>
                            <option value="">Pilih Jenis Pendaftaran</option>
                            <option value="Siswa Baru">Siswa Baru</option>
                            <option value="Pindahan">Pindahan</option>
                            <option value="Kembali Bersekolah">Kembali Bersekolah</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Sekolah Asal</label>
                        <input type="text"
                               name="sekolah_asal"
                               class="form-control"
                               placeholder="Nama sekolah sebelumnya">
                    </div>

                </div>

                <div class="col-md-6">

                    <div class="mb-3">
                        <label class="fw-bold">No Peserta UN</label>
                        <input type="text" name="no_peserta_un" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">No Seri Ijazah</label>
                        <input type="text" name="no_seri_ijazah" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">No SKHUN</label>
                        <input type="text" name="no_skhun" class="form-control">
                    </div>

                </div>

            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route($prefix.'registrasi-siswa.index') }}" class="btn btn-secondary">Kembali</a>
        </div>

    </form>
</div>

@endsection
