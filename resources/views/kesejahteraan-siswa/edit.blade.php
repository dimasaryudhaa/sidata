@extends('layouts.app')

@section('content')

@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    $isSiswa = $user->role === 'siswa';
    $prefix = $isAdmin ? 'admin.' : 'siswa.';
@endphp

<div class="container">
    <h2 class="mb-4">
        {{ $kesejahteraan->id ? 'Edit Data Kesejahteraan Siswa' : 'Tambah Data Kesejahteraan Siswa' }}
    </h2>

    <form action="{{ $kesejahteraan->id
        ? route($prefix . 'kesejahteraan-siswa.update', $kesejahteraan->id)
        : route($prefix . 'kesejahteraan-siswa.store') }}"
        method="POST">

        @csrf
        @if($kesejahteraan->id)
            @method('PUT')
        @endif

        <div class="row">

            <div class="col-md-6">

                <div class="mb-3">
                    <label>Nama Siswa</label>
                    <input type="text" class="form-control"
                           value="{{ $siswa->nama_lengkap ?? $kesejahteraan->siswa->nama_lengkap }}"
                           readonly>

                    <input type="hidden" name="peserta_didik_id"
                           value="{{ $siswa->id ?? $kesejahteraan->peserta_didik_id }}">
                </div>

                <div class="mb-3">
                    <label>Jenis Kesejahteraan</label>
                    <select name="jenis_kesejahteraan" class="form-control" required>
                        <option value="">Pilih Jenis</option>

                        @foreach([
                            'PKH',
                            'PIP',
                            'Kartu Perlindungan Sosial',
                            'Kartu Keluarga Sejahtera',
                            'Kartu Kesehatan'
                        ] as $jenis)

                            <option value="{{ $jenis }}"
                                {{ old('jenis_kesejahteraan', $kesejahteraan->jenis_kesejahteraan) == $jenis ? 'selected' : '' }}>
                                {{ $jenis }}
                            </option>

                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">

                <div class="mb-3">
                    <label>No Kartu</label>
                    <input type="text" name="no_kartu" class="form-control"
                           value="{{ old('no_kartu', $kesejahteraan->no_kartu) }}">
                </div>

                <div class="mb-3">
                    <label>Nama di Kartu</label>
                    <input type="text" name="nama_di_kartu" class="form-control"
                           value="{{ old('nama_di_kartu', $kesejahteraan->nama_di_kartu) }}">
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix . 'kesejahteraan-siswa.index') }}" class="btn btn-secondary me-2">
                Kembali
            </a>

            <button type="submit" class="btn btn-success">
                {{ $kesejahteraan->id ? 'Perbarui' : 'Simpan' }}
            </button>
        </div>

    </form>
</div>

@endsection
