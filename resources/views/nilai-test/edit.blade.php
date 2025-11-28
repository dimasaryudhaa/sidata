@extends('layouts.app')

@section('content')

@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    $prefix = $isAdmin ? 'admin' : 'ptk';
@endphp

<div class="container">
    <h1 class="mb-4">Edit Nilai Test PTK</h1>

    <form action="{{ route($prefix . '.nilai-test.update', $nilaiTest->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <input type="text" class="form-control"
                           value="{{ $ptk->nama_lengkap ?? $nilaiTest->ptk->nama_lengkap }}"
                           readonly>
                    <input type="hidden" name="ptk_id" value="{{ $ptk->id ?? $nilaiTest->ptk_id }}">
                </div>

                <div class="mb-3">
                    <label>Jenis Test</label>
                    <input type="text" name="jenis_test" class="form-control"
                           value="{{ $nilaiTest->jenis_test }}" required>
                </div>

                <div class="mb-3">
                    <label>Nama Test</label>
                    <input type="text" name="nama_test" class="form-control"
                           value="{{ $nilaiTest->nama_test }}" required>
                </div>

                <div class="mb-3">
                    <label>Penyelenggara</label>
                    <input type="text" name="penyelenggara" class="form-control"
                           value="{{ $nilaiTest->penyelenggara }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Tahun</label>
                    <input type="number" name="tahun" class="form-control"
                           value="{{ $nilaiTest->tahun }}" required>
                </div>

                <div class="mb-3">
                    <label>Skor</label>
                    <input type="number" step="0.01" name="skor" class="form-control"
                           value="{{ $nilaiTest->skor }}" required>
                </div>

                <div class="mb-3">
                    <label>Nomor Peserta</label>
                    <input type="text" name="nomor_peserta" class="form-control"
                           value="{{ $nilaiTest->nomor_peserta }}" required>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix . '.nilai-test.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>
    </form>
</div>

@endsection
