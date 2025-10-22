@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Rombel</h1>
    <form action="{{ route('rombel.store') }}" method="POST">
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
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
