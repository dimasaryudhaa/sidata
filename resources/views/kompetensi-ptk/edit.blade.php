@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Kompetensi PTK</h1>

    <form action="{{ route('kompetensi-ptk.update', $kompetensi->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <input type="text" class="form-control" value="{{ $ptk->nama_lengkap ?? $kompetensi->ptk->nama_lengkap }}" readonly>
                    <input type="hidden" name="ptk_id" value="{{ $ptk->id ?? $kompetensi->ptk_id }}">
                </div>

                <div class="mb-3">
                    <label>Bidang Studi</label>
                    <input type="text" name="bidang_studi" class="form-control" value="{{ $kompetensi->bidang_studi }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Urutan</label>
                    <input type="number" name="urutan" class="form-control" value="{{ $kompetensi->urutan }}">
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('kompetensi-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>
    </form>
</div>
@endsection
