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
    <h2 class="mb-4">Edit Data Beasiswa Siswa</h2>

    <form action="{{ route($prefix . 'beasiswa.update', $beasiswa->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama Siswa</label>
                    <input type="text" class="form-control"
                           value="{{ $siswa->nama_lengkap ?? $beasiswa->siswa->nama_lengkap }}"
                           readonly>
                    <input type="hidden" name="peserta_didik_id"
                           value="{{ $siswa->id ?? $beasiswa->peserta_didik_id }}">
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
                    <input type="text" name="keterangan" class="form-control"
                           value="{{ old('keterangan', $beasiswa->keterangan) }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Tahun Mulai</label>
                    <input type="number" name="tahun_mulai" class="form-control"
                           value="{{ old('tahun_mulai', $beasiswa->tahun_mulai) }}"
                           placeholder="YYYY" required>
                </div>

                <div class="mb-3">
                    <label>Tahun Selesai</label>
                    <input type="number" name="tahun_selesai" class="form-control"
                           value="{{ old('tahun_selesai', $beasiswa->tahun_selesai) }}"
                           placeholder="YYYY" required>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix . 'beasiswa.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>

    </form>
</div>

@endsection
