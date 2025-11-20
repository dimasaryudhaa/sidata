@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $isAdmin = $user->role === 'admin';
    $prefix = $isAdmin ? 'admin.' : 'ptk.';
@endphp

<div class="container">
    <h1>Tambah Pendidikan PTK</h1>

    <form action="{{ route($prefix.'pendidikan-ptk.store') }}" method="POST">
        @csrf
        <div class="row">

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    @if(isset($ptk))
                        <input type="text" class="form-control" value="{{ $ptk->nama_lengkap }}" readonly>
                        <input type="hidden" name="ptk_id" value="{{ $ptk->id }}">
                    @else
                        <select name="ptk_id" class="form-control" required>
                            <option value="">-- Pilih PTK --</option>
                            @foreach($ptks as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="mb-3">
                    <label>Bidang Studi</label>
                    <input type="text" name="bidang_studi" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Jenjang Pendidikan</label>
                    <input type="text" name="jenjang_pendidikan" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Gelar Akademik</label>
                    <input type="text" name="gelar_akademik" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Satuan Pendidikan Formal</label>
                    <input type="text" name="satuan_pendidikan_formal" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Fakultas</label>
                    <input type="text" name="fakultas" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Kependidikan</label>
                    <select name="kependidikan" class="form-control" required>
                        <option value="">Pilih</option>
                        <option value="Ya">Ya</option>
                        <option value="Tidak">Tidak</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Tahun Masuk</label>
                    <input type="number" name="tahun_masuk" class="form-control" min="1900" max="{{ date('Y') }}" required>
                </div>

                <div class="mb-3">
                    <label>Tahun Lulus</label>
                    <input type="number" name="tahun_lulus" class="form-control" min="1900" max="{{ date('Y') }}">
                </div>

                <div class="mb-3">
                    <label>Nomor Induk</label>
                    <input type="text" name="nomor_induk" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Masih Studi?</label>
                    <select name="masih_studi" class="form-control">
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Semester</label>
                    <input type="number" name="semester" class="form-control" min="1">
                </div>

                <div class="mb-3">
                    <label>Rata-rata Ujian</label>
                    <input type="number" step="0.01" name="rata_rata_ujian" class="form-control">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix.'pendidikan-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection
