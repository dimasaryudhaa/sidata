@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Rayon</h1>
    <form action="{{ route('rayon.update', $rayon->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>PTK ID</label>
            <input type="number" name="ptk_id" class="form-control" value="{{ $rayon->ptk_id }}" required>
        </div>
        <div class="mb-3">
            <label>Nama Rayon</label>
            <input type="text" name="nama_rayon" class="form-control" value="{{ $rayon->nama_rayon }}" required>
        </div>
        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('rayon.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Update</button>
        </div>
    </form>
</div>
@endsection
