@extends('layouts.app')

@section('content')

@php
    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    $prefix = $isAdmin ? 'admin' : 'ptk';
@endphp

<style>
    body, html {
        overflow: hidden;
    }
</style>

<div class="container">
    <h1 class="mb-4">Edit Riwayat Jabatan Fungsional</h1>

    <form action="{{ route($prefix . '.riwayat-jabatan-fungsional.update', $riwayatJabatanFungsional->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">

                <div class="mb-3">
                    <label>Nama PTK</label>
                    <input type="text" class="form-control"
                           value="{{ $ptk->nama_lengkap ?? $riwayatJabatanFungsional->ptk->nama_lengkap }}"
                           readonly>

                    <input type="hidden" name="ptk_id"
                           value="{{ $ptk->id ?? $riwayatJabatanFungsional->ptk_id }}">
                </div>

                <div class="mb-3">
                    <label>Jabatan Fungsional</label>
                    <input type="text" name="jabatan_fungsional" class="form-control"
                           value="{{ $riwayatJabatanFungsional->jabatan_fungsional }}" required>
                </div>

            </div>


            <div class="col-md-6">

                <div class="mb-3">
                    <label>Nomor SK Jabfung</label>
                    <input type="text" name="sk_jabfung" class="form-control"
                           value="{{ $riwayatJabatanFungsional->sk_jabfung }}" required>
                </div>

                <div class="mb-3">
                    <label>TMT Jabatan</label>
                    <input type="date" name="tmt_jabatan" class="form-control"
                           value="{{ $riwayatJabatanFungsional->tmt_jabatan }}" required>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix . '.riwayat-jabatan-fungsional.index') }}"
               class="btn btn-secondary me-2">Kembali</a>

            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>

    </form>
</div>

@endsection
