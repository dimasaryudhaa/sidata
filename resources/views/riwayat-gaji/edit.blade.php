@extends('layouts.app')

@section('content')

<div class="container">

    <h1 class="mb-4">Edit Riwayat Gaji</h1>

    @php
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin' : 'ptk';
    @endphp

    <form action="{{ route($prefix . '.riwayat-gaji.update', $riwayatGaji->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <input type="text" class="form-control"
                        value="{{ $ptk->nama_lengkap ?? $riwayatGaji->ptk->nama_lengkap }}"
                        readonly>

                    <input type="hidden" name="ptk_id"
                        value="{{ $ptk->id ?? $riwayatGaji->ptk_id }}">
                </div>

                <div class="mb-3">
                    <label>Pangkat Golongan</label>
                    <input type="text" name="pangkat_golongan" class="form-control"
                           value="{{ $riwayatGaji->pangkat_golongan }}" required>
                </div>

                <div class="mb-3">
                    <label>Nomor SK</label>
                    <input type="text" name="nomor_sk" class="form-control"
                           value="{{ $riwayatGaji->nomor_sk }}" required>
                </div>

                <div class="mb-3">
                    <label>Tanggal SK</label>
                    <input type="date" name="tanggal_sk" class="form-control"
                           value="{{ $riwayatGaji->tanggal_sk }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>TMT KGB</label>
                    <input type="date" name="tmt_kgb" class="form-control"
                           value="{{ $riwayatGaji->tmt_kgb }}" required>
                </div>

                <div class="mb-3">
                    <label>Masa Kerja (Tahun)</label>
                    <input type="number" name="masa_kerja_thn" class="form-control"
                           value="{{ $riwayatGaji->masa_kerja_thn }}" min="0" required>
                </div>

                <div class="mb-3">
                    <label>Masa Kerja (Bulan)</label>
                    <input type="number" name="masa_kerja_bln" class="form-control"
                           value="{{ $riwayatGaji->masa_kerja_bln }}" min="0" max="11" required>
                </div>

                <div class="mb-3">
                    <label>Gaji Pokok</label>
                    <input type="number" name="gaji_pokok" class="form-control"
                           value="{{ $riwayatGaji->gaji_pokok }}" min="0" step="0.01" required>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix . '.riwayat-gaji.index') }}"
               class="btn btn-secondary me-2">Kembali</a>

            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>
    </form>
</div>

@endsection
