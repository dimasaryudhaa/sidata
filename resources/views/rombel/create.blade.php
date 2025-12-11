@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Rombel</h1>
    <form action="{{ route('admin.rombel.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Jurusan</label>
            <select name="jurusan_id" class="form-control" required>
                <option value="">Pilih Jurusan</option>
                @foreach($jurusan as $j)
                <option value="{{ $j->id }}">{{ $j->nama_jurusan }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Nama Rombel</label>
            <input type="text" name="nama_rombel" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tingkat</label>
            <select name="tingkat" class="form-control">
                <option value="">Pilih Tingkat</option>
                <option value="X">X</option>
                <option value="XI">XI</option>
                <option value="XII">XII</option>
            </select>
        </div>
        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('admin.rombel.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection
