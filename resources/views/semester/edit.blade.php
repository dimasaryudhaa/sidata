@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Semester</h1>
    <form action="{{ route('semester.update', $semester->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nama Semester</label>
            <input type="text" name="nama_semester" class="form-control" value="{{ $semester->nama_semester }}" required>
        </div>
        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('semester.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Update</button>
        </div>
    </form>
</div>
@endsection
