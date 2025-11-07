@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Kesejahteraan Siswa</h1>

    <form action="{{ route('kesejahteraan-siswa.update', $kesejahteraan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama Siswa</label>
                    <input type="text" class="form-control" value="{{ $kesejahteraan->siswa->nama_lengkap ?? '-' }}" readonly>
                    <input type="hidden" name="peserta_didik_id" value="{{ $kesejahteraan->peserta_didik_id }}">
                </div>

                <div class="mb-3">
                    <label>Jenis Kesejahteraan</label>
                    <select name="jenis_kesejahteraan" class="form-control" required>
                        <option value="PKH" {{ $kesejahteraan->jenis_kesejahteraan == 'PKH' ? 'selected' : '' }}>PKH</option>
                        <option value="PIP" {{ $kesejahteraan->jenis_kesejahteraan == 'PIP' ? 'selected' : '' }}>PIP</option>
                        <option value="Kartu Perlindungan Sosial" {{ $kesejahteraan->jenis_kesejahteraan == 'Kartu Perlindungan Sosial' ? 'selected' : '' }}>Kartu Perlindungan Sosial</option>
                        <option value="Kartu Keluarga Sejahtera" {{ $kesejahteraan->jenis_kesejahteraan == 'Kartu Keluarga Sejahtera' ? 'selected' : '' }}>Kartu Keluarga Sejahtera</option>
                        <option value="Kartu Kesehatan" {{ $kesejahteraan->jenis_kesejahteraan == 'Kartu Kesehatan' ? 'selected' : '' }}>Kartu Kesehatan</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>No Kartu</label>
                    <input type="text" name="no_kartu" value="{{ $kesejahteraan->no_kartu }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Nama di Kartu</label>
                    <input type="text" name="nama_di_kartu" value="{{ $kesejahteraan->nama_di_kartu }}" class="form-control">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <a href="{{ route('kesejahteraan-siswa.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Update</button>
        </div>
    </form>
</div>
@endsection
