@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Jurusan</h1>
    <form action="{{ route('admin.jurusan.update', $jurusan->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nama Jurusan</label>
            <input type="text" name="nama_jurusan" class="form-control" value="{{ $jurusan->nama_jurusan }}" required>
        </div>
        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('admin.jurusan.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>
    </form>
</div>
@endsection
