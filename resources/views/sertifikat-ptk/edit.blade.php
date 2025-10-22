@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Sertifikat PTK</h1>

    <form action="{{ route('sertifikat-ptk.update', $sertifikatPtk->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <input type="text" class="form-control" value="{{ $ptk->nama_lengkap ?? $sertifikatPtk->ptk->nama_lengkap }}" readonly>
                    <input type="hidden" name="ptk_id" value="{{ $ptk->id ?? $sertifikatPtk->ptk_id }}">
                </div>
                <div class="mb-3">
                    <label>Jenis Sertifikasi</label>
                    <input type="text" name="jenis_sertifikasi" class="form-control" value="{{ $sertifikatPtk->jenis_sertifikasi }}" required>
                </div>
                <div class="mb-3">
                    <label>Nomor Sertifikat</label>
                    <input type="text" name="nomor_sertifikat" class="form-control" value="{{ $sertifikatPtk->nomor_sertifikat }}" required>
                </div>
                <div class="mb-3">
                    <label>Tahun Sertifikasi</label>
                    <input type="number" name="tahun_sertifikasi" class="form-control" value="{{ $sertifikatPtk->tahun_sertifikasi }}" required>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Bidang Studi</label>
                    <input type="text" name="bidang_studi" class="form-control" value="{{ $sertifikatPtk->bidang_studi }}" required>
                </div>
                <div class="mb-3">
                    <label>NRG</label>
                    <input type="text" name="nrg" class="form-control" value="{{ $sertifikatPtk->nrg }}" required>
                </div>
                <div class="mb-3">
                    <label>Nomor Peserta</label>
                    <input type="text" name="nomor_peserta" class="form-control" value="{{ $sertifikatPtk->nomor_peserta }}" required>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('sertifikat-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>
    </form>
</div>
@endsection
