@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Rombel</h1>
    <form action="{{ route('admin.rombel.update', $rombel->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Jurusan</label>
            <select name="jurusan_id" class="form-control" required>
                @foreach($jurusan as $j)
                <option value="{{ $j->id }}" {{ $j->id == $rombel->jurusan_id ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Nama Rombel</label>
            <input type="text" name="nama_rombel" class="form-control" value="{{ $rombel->nama_rombel }}" required>
        </div>
        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('admin.rombel.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>
    </form>
</div>
@endsection
