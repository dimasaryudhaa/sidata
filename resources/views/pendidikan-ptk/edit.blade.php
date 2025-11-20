@extends('layouts.app')

@section('content')

@php
    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    $prefix = $isAdmin ? 'admin' : 'ptk';
@endphp

<div class="container">
    <h1 class="mb-4">Edit Pendidikan PTK</h1>

    <form action="{{ route($prefix . '.pendidikan-ptk.update', $pendidikanPtk->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <input type="text" class="form-control" value="{{ $ptk->nama_lengkap ?? $pendidikanPtk->ptk->nama_lengkap }}" readonly>
                    <input type="hidden" name="ptk_id" value="{{ $ptk->id ?? $pendidikanPtk->ptk_id }}">
                </div>

                <div class="mb-3">
                    <label>Bidang Studi</label>
                    <input type="text" name="bidang_studi" class="form-control"
                           value="{{ old('bidang_studi', $pendidikanPtk->bidang_studi) }}" required>
                </div>

                <div class="mb-3">
                    <label>Jenjang Pendidikan</label>
                    <input type="text" name="jenjang_pendidikan" class="form-control"
                           value="{{ old('jenjang_pendidikan', $pendidikanPtk->jenjang_pendidikan) }}" required>
                </div>

                <div class="mb-3">
                    <label>Gelar Akademik</label>
                    <input type="text" name="gelar_akademik" class="form-control"
                           value="{{ old('gelar_akademik', $pendidikanPtk->gelar_akademik) }}" required>
                </div>

                <div class="mb-3">
                    <label>Satuan Pendidikan Formal</label>
                    <input type="text" name="satuan_pendidikan_formal" class="form-control"
                           value="{{ old('satuan_pendidikan_formal', $pendidikanPtk->satuan_pendidikan_formal) }}" required>
                </div>

                <div class="mb-3">
                    <label>Fakultas</label>
                    <input type="text" name="fakultas" class="form-control"
                           value="{{ old('fakultas', $pendidikanPtk->fakultas) }}" required>
                </div>

                <div class="mb-3">
                    <label>Kependidikan</label>
                    <select name="kependidikan" class="form-control" required>
                        <option value="Ya" {{ $pendidikanPtk->kependidikan == 'Ya' ? 'selected' : '' }}>Ya</option>
                        <option value="Tidak" {{ $pendidikanPtk->kependidikan == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Tahun Masuk</label>
                    <input type="text" name="tahun_masuk" class="form-control"
                           value="{{ old('tahun_masuk', $pendidikanPtk->tahun_masuk) }}" required>
                </div>

                <div class="mb-3">
                    <label>Tahun Lulus</label>
                    <input type="text" name="tahun_lulus" class="form-control"
                           value="{{ old('tahun_lulus', $pendidikanPtk->tahun_lulus) }}" required>
                </div>

                <div class="mb-3">
                    <label>Nomor Induk</label>
                    <input type="text" name="nomor_induk" class="form-control"
                           value="{{ old('nomor_induk', $pendidikanPtk->nomor_induk) }}" required>
                </div>

                <div class="mb-3">
                    <label>Masih Studi?</label>
                    <select name="masih_studi" class="form-control" required>
                        <option value="1" {{ $pendidikanPtk->masih_studi ? 'selected' : '' }}>Ya</option>
                        <option value="0" {{ !$pendidikanPtk->masih_studi ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Semester</label>
                    <input type="number" name="semester" class="form-control"
                           value="{{ old('semester', $pendidikanPtk->semester) }}" required>
                </div>

                <div class="mb-3">
                    <label>Rata-rata Ujian</label>
                    <input type="number" step="0.01" name="rata_rata_ujian" class="form-control"
                           value="{{ old('rata_rata_ujian', $pendidikanPtk->rata_rata_ujian) }}" required>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix . '.pendidikan-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>
    </form>
</div>

@endsection
