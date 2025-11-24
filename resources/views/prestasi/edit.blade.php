@extends('layouts.app')

@section('content')

@php
    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    $isSiswa = $user->role === 'siswa';
    $prefix = $isAdmin ? 'admin.' : 'siswa.';
@endphp

<div class="container">
    <h2 class="mb-4">
        {{ $prestasi->id ? 'Edit Data Prestasi Siswa' : 'Tambah Data Prestasi Siswa' }}
    </h2>

    <form action="{{ $prestasi->id
        ? route($prefix . 'prestasi.update', $prestasi->id)
        : route($prefix . 'prestasi.store') }}"
        method="POST">

        @csrf
        @if($prestasi->id)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama Siswa</label>
                    <input type="text" class="form-control"
                        value="{{ $siswa->nama_lengkap ?? $prestasi->siswa->nama_lengkap }}"
                        readonly>

                    <input type="hidden" name="peserta_didik_id"
                        value="{{ $siswa->id ?? $prestasi->peserta_didik_id }}">
                </div>

                <div class="mb-3">
                    <label>Jenis Prestasi</label>
                    <select name="jenis_prestasi" class="form-control" required>
                        <option value="">Pilih Jenis</option>
                        @foreach(['Sains','Seni','Olahraga','Lain-lain'] as $jenis)
                            <option value="{{ $jenis }}"
                                {{ old('jenis_prestasi', $prestasi->jenis_prestasi) == $jenis ? 'selected' : '' }}>
                                {{ $jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tingkat Prestasi</label>
                    <select name="tingkat_prestasi" class="form-control" required>
                        <option value="">Pilih Tingkat</option>
                        @foreach(['Sekolah','Kecamatan','Kabupaten','Provinsi','Nasional','Internasional'] as $t)
                            <option value="{{ $t }}"
                                {{ old('tingkat_prestasi', $prestasi->tingkat_prestasi) == $t ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Nama Prestasi</label>
                    <input type="text" name="nama_prestasi" class="form-control"
                        value="{{ old('nama_prestasi', $prestasi->nama_prestasi) }}" required>
                </div>

            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Tahun Prestasi</label>
                    <input type="number" name="tahun_prestasi" class="form-control"
                        value="{{ old('tahun_prestasi', $prestasi->tahun_prestasi) }}"
                        placeholder="YYYY" required>
                </div>

                <div class="mb-3">
                    <label>Penyelenggara</label>
                    <input type="text" name="penyelenggara" class="form-control"
                        value="{{ old('penyelenggara', $prestasi->penyelenggara) }}" required>
                </div>

                <div class="mb-3">
                    <label>Peringkat</label>
                    <input type="number" name="peringkat" class="form-control"
                        value="{{ old('peringkat', $prestasi->peringkat) }}">
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix . 'prestasi.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">
                {{ $prestasi->id ? 'Perbarui' : 'Simpan' }}
            </button>
        </div>

    </form>
</div>

@endsection
