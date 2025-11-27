@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Semester</h1>
    <form action="{{ route('admin.semester.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nama Semester</label>
            <select name="nama_semester" class="form-control" required>
                <option value="">Pilih Semester</option>
                <option value="Ganjil">Ganjil</option>
                <option value="Genap">Genap</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Tahun Ajaran</label>
            <input type="text" name="tahun_ajaran" class="form-control" required>
        </div>
        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('admin.semester.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection
