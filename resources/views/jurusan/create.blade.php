@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Jurusan</h1>
    <form action="{{ route('admin.jurusan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama Jurusan</label>
            <input type="text" name="nama_jurusan" class="form-control" required>
        </div>
        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('admin.jurusan.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection
