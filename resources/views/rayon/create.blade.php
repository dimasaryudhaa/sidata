@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Rayon</h1>
    <form action="{{ route('admin.rayon.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>PTK</label>
            <select name="ptk_id" class="form-control" required>
                <option value="">Pilih PTK</option>
                @foreach($ptks as $ptk)
                    <option value="{{ $ptk->id }}">{{ $ptk->nama_lengkap }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Nama Rayon</label>
            <input type="text" name="nama_rayon" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
