@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Penghargaan PTK</h1>

    <form action="{{ route('penghargaan.update', $penghargaan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <input type="text" class="form-control" value="{{ $ptk->nama_lengkap ?? $penghargaan->ptk->nama_lengkap }}" readonly>
                    <input type="hidden" name="ptk_id" value="{{ $ptk->id ?? $penghargaan->ptk_id }}">
                </div>

                <div class="mb-3">
                    <label>Tingkat Penghargaan</label>
                    <input type="text" name="tingkat_penghargaan" class="form-control" value="{{ $penghargaan->tingkat_penghargaan }}" required>
                </div>

                <div class="mb-3">
                    <label>Jenis Penghargaan</label>
                    <input type="text" name="jenis_penghargaan" class="form-control" value="{{ $penghargaan->jenis_penghargaan }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama Penghargaan</label>
                    <input type="text" name="nama_penghargaan" class="form-control" value="{{ $penghargaan->nama_penghargaan }}" required>
                </div>

                <div class="mb-3">
                    <label>Tahun</label>
                    <input type="number" name="tahun" class="form-control" value="{{ $penghargaan->tahun }}" required>
                </div>

                <div class="mb-3">
                    <label>Instansi</label>
                    <input type="text" name="instansi" class="form-control" value="{{ $penghargaan->instansi }}" required>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('penghargaan.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>
    </form>
</div>
@endsection
