@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';
@endphp

<div class="container">
    <h3>{{ isset($tunjangan) ? 'Edit Tunjangan PTK' : 'Tambah Tunjangan PTK' }}</h3>

    <form action="{{ isset($tunjangan) ? route($prefix.'tunjangan.update', $tunjangan->id) : route($prefix.'tunjangan.store') }}" method="POST">
        @csrf
        @if(isset($tunjangan))
            @method('PUT')
        @endif

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
                                <option value="{{ $p->id }}" {{ (old('ptk_id', $tunjangan->ptk_id ?? '') == $p->id) ? 'selected' : '' }}>
                                    {{ $p->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="mb-3">
                    <label>Jenis Tunjangan</label>
                    <input type="text" name="jenis_tunjangan" class="form-control"
                           value="{{ old('jenis_tunjangan', $tunjangan->jenis_tunjangan ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label>Nama Tunjangan</label>
                    <input type="text" name="nama_tunjangan" class="form-control"
                           value="{{ old('nama_tunjangan', $tunjangan->nama_tunjangan ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label>Instansi</label>
                    <input type="text" name="instansi" class="form-control"
                           value="{{ old('instansi', $tunjangan->instansi ?? '') }}">
                </div>

                <div class="mb-3">
                    <label>Nomor SK Tunjangan</label>
                    <input type="text" name="sk_tunjangan" class="form-control"
                           value="{{ old('sk_tunjangan', $tunjangan->sk_tunjangan ?? '') }}">
                </div>

                <div class="mb-3">
                    <label>Tanggal SK Tunjangan</label>
                    <input type="date" name="tgl_sk_tunjangan" class="form-control"
                           value="{{ old('tgl_sk_tunjangan', isset($tunjangan) && $tunjangan->tgl_sk_tunjangan ? \Carbon\Carbon::parse($tunjangan->tgl_sk_tunjangan)->format('Y-m-d') : '') }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Semester</label>
                    <select name="semester_id" class="form-control" required>
                        <option value="">Pilih Semester</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ (old('semester_id', $tunjangan->semester_id ?? '') == $semester->id) ? 'selected' : '' }}>
                                {{ $semester->nama_semester }} - {{ $semester->tahun_ajaran }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Sumber Dana</label>
                    <input type="text" name="sumber_dana" class="form-control"
                           value="{{ old('sumber_dana', $tunjangan->sumber_dana ?? '') }}">
                </div>

                <div class="mb-3">
                    <label>Dari Tahun</label>
                    <input type="number" name="dari_tahun" class="form-control" min="1900" max="2100"
                           value="{{ old('dari_tahun', $tunjangan->dari_tahun ?? '') }}">
                </div>

                <div class="mb-3">
                    <label>Sampai Tahun</label>
                    <input type="number" name="sampai_tahun" class="form-control" min="1900" max="2100"
                           value="{{ old('sampai_tahun', $tunjangan->sampai_tahun ?? '') }}">
                </div>

                <div class="mb-3">
                    <label>Nominal (Rp)</label>
                    <input type="number" name="nominal" class="form-control" min="0" step="0.01"
                           value="{{ old('nominal', $tunjangan->nominal ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="Masih Menerima" {{ old('status', $tunjangan->status ?? '') == 'Masih Menerima' ? 'selected' : '' }}>Masih Menerima</option>
                        <option value="Tidak Menerima" {{ old('status', $tunjangan->status ?? '') == 'Tidak Menerima' ? 'selected' : '' }}>Tidak Menerima</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix.'tunjangan.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">{{ isset($tunjangan) ? 'Update' : 'Simpan' }}</button>
        </div>
    </form>
</div>

@endsection
