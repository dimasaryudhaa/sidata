@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Riwayat Jabatan PTK</h1>

    <form action="{{ route('riwayat-jabatan.update', $riwayatJabatan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <input type="text" class="form-control" value="{{ $ptk->nama_lengkap ?? $riwayatJabatan->ptk->nama_lengkap }}" readonly>
                    <input type="hidden" name="ptk_id" value="{{ $ptk->id ?? $riwayatJabatan->ptk_id }}">
                </div>

                <div class="mb-3">
                    <label>Jabatan PTK</label>
                    <input type="text" name="jabatan_ptk" class="form-control" value="{{ $riwayatJabatan->jabatan_ptk }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>SK Jabatan</label>
                    <input type="text" name="sk_jabatan" class="form-control" value="{{ $riwayatJabatan->sk_jabatan }}" required>
                </div>

                <div class="mb-3">
                    <label>TMT Jabatan</label>
                    <input type="date" name="tmt_jabatan" class="form-control" value="{{ $riwayatJabatan->tmt_jabatan }}" required>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('riwayat-jabatan.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Update</button>
        </div>
    </form>
</div>
@endsection
