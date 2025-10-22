@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Jurusan</h1>
    <form action="{{ route('jurusan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama Jurusan</label>
            <input type="text" name="nama_jurusan" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
