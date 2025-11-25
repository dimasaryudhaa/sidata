@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Semester</h1>

    <form action="{{ route('admin.semester.update', $semester->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Semester</label>
            <select name="nama_semester" class="form-control" required>
                <option value="Ganjil" {{ $semester->nama_semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                <option value="Genap"  {{ $semester->nama_semester == 'Genap'  ? 'selected' : '' }}>Genap</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Tahun Ajaran</label>
            <input type="text" name="tahun_ajaran" class="form-control"
                   placeholder="Contoh: 2024/2025"
                   value="{{ $semester->tahun_ajaran }}" required>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('admin.semester.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>
    </form>
</div>
@endsection
