@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Akun PTK</h1>

    <form action="{{ route('akun-ptk.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <select name="ptk_id" class="form-control" required>
                        <option value="">-- Pilih PTK --</option>
                        @foreach($ptks as $ptk)
                            <option value="{{ $ptk->id }}">{{ $ptk->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="contoh: guru@email.com">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Masukkan password akun">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <a href="{{ route('akun-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection
