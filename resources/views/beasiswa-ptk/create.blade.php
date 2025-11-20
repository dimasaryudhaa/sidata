@extends('layouts.app')

@section('content')
@php
    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    $prefix = $isAdmin ? 'admin.' : 'ptk.';
@endphp

<div class="container">
    <h1>Tambah Beasiswa PTK</h1>

    <form action="{{ route($prefix.'beasiswa-ptk.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    @if(isset($ptkId))
                        <input type="text" class="form-control" value="{{ $ptk->nama_lengkap }}" readonly>
                        <input type="hidden" name="ptk_id" value="{{ $ptkId }}">
                    @else
                        <select name="ptk_id" class="form-control" required>
                            <option value="">-- Pilih PTK --</option>
                            @foreach($ptks as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="mb-3">
                    <label>Jenis Beasiswa</label>
                    <input type="text" name="jenis_beasiswa" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Tahun Mulai</label>
                    <input type="number" name="tahun_mulai" class="form-control" min="1900" max="{{ date('Y') }}" required>
                </div>

                <div class="mb-3">
                    <label>Tahun Akhir</label>
                    <input type="number" name="tahun_akhir" class="form-control" min="1900" max="{{ date('Y') + 5 }}" required>
                </div>

                <div class="mb-3">
                    <label>Masih Menerima</label>
                    <select name="masih_menerima" class="form-control" required>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix.'beasiswa-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection
