@extends('layouts.app')

@section('content')

@php
    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    $prefix = $isAdmin ? 'admin' : 'ptk';
@endphp

<div class="container">
    <h1 class="mb-4">Edit Riwayat Kepangkatan</h1>

    <form action="{{ route($prefix . '.riwayat-kepangkatan.update', $riwayatKepangkatan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">

                <div class="mb-3">
                    <label>Nama PTK</label>
                    <input type="text" class="form-control"
                           value="{{ $ptk->nama_lengkap ?? $riwayatKepangkatan->ptk->nama_lengkap }}"
                           readonly>
                    <input type="hidden" name="ptk_id"
                           value="{{ $ptk->id ?? $riwayatKepangkatan->ptk_id }}">
                </div>

                <div class="mb-3">
                    <label>Pangkat Golongan</label>
                    <input type="text" name="pangkat_golongan" class="form-control"
                           value="{{ $riwayatKepangkatan->pangkat_golongan }}" required>
                </div>

                <div class="mb-3">
                    <label>Nomor SK</label>
                    <input type="text" name="nomor_sk" class="form-control"
                           value="{{ $riwayatKepangkatan->nomor_sk }}" required>
                </div>

                <div class="mb-3">
                    <label>Tanggal SK</label>
                    <input type="date" name="tanggal_sk" class="form-control"
                           value="{{ $riwayatKepangkatan->tanggal_sk }}" required>
                </div>

            </div>

            <div class="col-md-6">

                <div class="mb-3">
                    <label>TMT Pangkat</label>
                    <input type="date" name="tmt_pangkat" class="form-control"
                           value="{{ $riwayatKepangkatan->tmt_pangkat }}" required>
                </div>

                <div class="mb-3">
                    <label>Masa Kerja (Tahun)</label>
                    <input type="number" name="masa_kerja_thn" class="form-control"
                           value="{{ $riwayatKepangkatan->masa_kerja_thn }}" min="0" required>
                </div>

                <div class="mb-3">
                    <label>Masa Kerja (Bulan)</label>
                    <input type="number" name="masa_kerja_bln" class="form-control"
                           value="{{ $riwayatKepangkatan->masa_kerja_bln }}" min="0" max="11" required>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix . '.riwayat-kepangkatan.index') }}"
               class="btn btn-secondary me-2">Kembali</a>

            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>

    </form>
</div>

@endsection
