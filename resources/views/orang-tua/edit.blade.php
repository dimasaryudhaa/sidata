@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Data Orang Tua / Wali</h1>
    <form
        action="{{ $data->id ? route('orang-tua.update', $data->id) : route('orang-tua.store') }}"
        method="POST"
    >
        @csrf
        @if($data->id)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Peserta Didik</label>
                    <input type="text" class="form-control"value="{{ $data->siswa->nama_lengkap ?? '-' }}" readonly>
                    <input type="hidden" name="peserta_didik_id" value="{{ $data->peserta_didik_id }}">
                </div>
                <div class="mb-3"><label>Nama Ayah</label><input type="text" name="nama_ayah" class="form-control" value="{{ $data->nama_ayah }}"></div>
            </div>
            <div class="col-md-6">
                <div class="mb-3"><label>Nama Ibu</label><input type="text" name="nama_ibu" class="form-control" value="{{ $data->nama_ibu }}"></div>
                <div class="mb-3"><label>Nama Wali</label><input type="text" name="nama_wali" class="form-control" value="{{ $data->nama_wali }}"></div>
            </div>
        </div>
        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('orang-tua.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>
    </form>
</div>
@endsection
