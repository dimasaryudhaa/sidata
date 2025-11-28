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
    <h2 class="mb-4">Edit Kompetensi PTK</h2>

    <form action="{{ route($prefix. 'kompetensi-ptk.update', $kompetensiPtk->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <input type="text" class="form-control" value="{{ $ptk->nama_lengkap ?? $kompetensiPtk->ptk->nama_lengkap }}" readonly>
                    <input type="hidden" name="ptk_id" value="{{ $ptk->id ?? $kompetensiPtk->ptk_id }}">
                </div>

                <div class="mb-3">
                    <label>Bidang Studi</label>
                    <input type="text" name="bidang_studi" class="form-control" value="{{ old('bidang_studi', $kompetensiPtk->bidang_studi) }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Urutan</label>
                    <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $kompetensiPtk->urutan) }}">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix. 'kompetensi-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>
    </form>
</div>

@endsection
