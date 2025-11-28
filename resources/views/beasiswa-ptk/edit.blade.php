@extends('layouts.app')

@section('content')

@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    $isPtk = $user->role === 'ptk';
    $prefix = $isAdmin ? 'admin.' : 'ptk.';
@endphp

<div class="container">
    <h2 class="mb-4">Edit Data Beasiswa PTK</h2>

    <form action="{{ route($prefix . 'beasiswa-ptk.update', $beasiswaPtk->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <input type="text" class="form-control" value="{{ $ptk->nama_lengkap ?? $beasiswaPtk->ptk->nama_lengkap }}" readonly>
                    <input type="hidden" name="ptk_id" value="{{ $ptk->id ?? $beasiswaPtk->ptk_id }}">
                </div>

                <div class="mb-3">
                    <label>Jenis Beasiswa</label>
                    <input type="text" name="jenis_beasiswa" class="form-control" value="{{ old('jenis_beasiswa', $beasiswaPtk->jenis_beasiswa) }}" required>
                </div>

                <div class="mb-3">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control">{{ old('keterangan', $beasiswaPtk->keterangan) }}</textarea>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Tahun Mulai</label>
                    <input type="number" name="tahun_mulai" class="form-control" value="{{ old('tahun_mulai', $beasiswaPtk->tahun_mulai) }}" required>
                </div>

                <div class="mb-3">
                    <label>Tahun Akhir</label>
                    <input type="number" name="tahun_akhir" class="form-control" value="{{ old('tahun_akhir', $beasiswaPtk->tahun_akhir) }}" required>
                </div>

                <div class="mb-3">
                    <label>Masih Menerima</label>
                    <select name="masih_menerima" class="form-control">
                        <option value="1" {{ $beasiswaPtk->masih_menerima == 1 ? 'selected' : '' }}>Ya</option>
                        <option value="0" {{ $beasiswaPtk->masih_menerima == 0 ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix . 'beasiswa-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>
    </form>
</div>

@endsection
