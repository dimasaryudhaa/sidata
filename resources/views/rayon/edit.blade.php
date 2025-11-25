@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Rayon</h1>
    <form action="{{ route('admin.rayon.update', $rayon->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nama PTK</label>
                <select name="ptk_id" class="form-control" required>
                    <option value="">-- Pilih PTK --</option>

                    @foreach ($ptks as $ptk)
                        <option value="{{ $ptk->id }}"
                            {{ $rayon->ptk_id == $ptk->id ? 'selected' : '' }}>
                            {{ $ptk->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
        </div>
        <div class="mb-3">
            <label>Nama Rayon</label>
            <input type="text" name="nama_rayon" class="form-control" value="{{ $rayon->nama_rayon }}" required>
        </div>
        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('admin.rayon.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>
    </form>
</div>
@endsection
