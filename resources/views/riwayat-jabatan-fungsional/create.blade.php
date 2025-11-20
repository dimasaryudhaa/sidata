@extends('layouts.app')

@section('content')

@php
    $user = auth()->user();
    $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';
@endphp

<style>
    body, html {
        overflow: hidden;
    }
</style>

<div class="container">
    <h1>Tambah Riwayat Jabatan Fungsional</h1>

    <form action="{{ route($prefix.'riwayat-jabatan-fungsional.store') }}" method="POST">
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
                    <label>Jabatan Fungsional</label>
                    <input type="text" name="jabatan_fungsional" class="form-control"
                           placeholder="Contoh: Guru Madya, Guru Pertama" required>
                </div>

            </div>

            <div class="col-md-6">

                <div class="mb-3">
                    <label>Nomor SK Jabfung</label>
                    <input type="text" name="sk_jabfung" class="form-control"
                           placeholder="Nomor SK Jabfung" required>
                </div>

                <div class="mb-3">
                    <label>TMT Jabatan</label>
                    <input type="date" name="tmt_jabatan" class="form-control" required>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix.'riwayat-jabatan-fungsional.index') }}" class="btn btn-secondary me-2">
                Kembali
            </a>

            <button type="submit" class="btn btn-success">Simpan</button>
        </div>

    </form>
</div>

@endsection
