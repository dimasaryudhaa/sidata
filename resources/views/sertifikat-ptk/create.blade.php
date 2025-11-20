@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $isAdmin = $user->role === 'admin';
    $prefix = $isAdmin ? 'admin.' : 'ptk.';
@endphp

<div class="container">
    <h1>Tambah Sertifikat PTK</h1>

    <form action="{{ route($prefix.'sertifikat-ptk.store') }}" method="POST">
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
                    <label>Jenis Sertifikasi</label>
                    <input type="text" name="jenis_sertifikasi" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Nomor Sertifikat</label>
                    <input type="text" name="nomor_sertifikat" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Tahun Sertifikasi</label>
                    <input type="number" name="tahun_sertifikasi" class="form-control" min="1900" max="{{ date('Y') }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Bidang Studi</label>
                    <input type="text" name="bidang_studi" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>NRG</label>
                    <input type="text" name="nrg" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Nomor Peserta</label>
                    <input type="text" name="nomor_peserta" class="form-control">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix.'sertifikat-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection
