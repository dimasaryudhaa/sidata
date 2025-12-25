@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    $prefix = $isAdmin ? 'admin.' : 'siswa.';
@endphp

<div class="container">
    <h1>Tambah Kesejahteraan Siswa</h1>

    <form action="{{ route($prefix . 'kesejahteraan-siswa.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama Siswa</label>
                    @if(isset($siswaId))
                        <input type="text" class="form-control"
                            value="{{ $siswa->nama_lengkap }}" readonly>
                        <input type="hidden" name="peserta_didik_id"
                            value="{{ $siswaId }}">

                    @elseif($isAdmin)
                        <select name="peserta_didik_id" class="form-control" required>
                            <option value="">Pilih Siswa</option>
                            @foreach($siswa as $s)
                                <option value="{{ $s->id }}">{{ $s->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" class="form-control"
                            value="{{ $siswa->nama_lengkap }}" readonly>
                        <input type="hidden" name="peserta_didik_id"
                            value="{{ $siswa->id }}">
                    @endif
                </div>

                <div class="mb-3">
                    <label>Jenis Kesejahteraan</label>
                    <select name="jenis_kesejahteraan" class="form-control" required>
                        <option value="">Pilih Jenis Kesejahteraan</option>
                        <option value="PKH">PKH</option>
                        <option value="PIP">PIP</option>
                        <option value="Kartu Perlindungan Sosial">Kartu Perlindungan Sosial</option>
                        <option value="Kartu Keluarga Sejahtera">Kartu Keluarga Sejahtera</option>
                        <option value="Kartu Kesehatan">Kartu Kesehatan</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>No Kartu</label>
                    <input type="text" name="no_kartu" class="form-control" placeholder="Masukkan nomor kartu">
                </div>

                <div class="mb-3">
                    <label>Nama di Kartu</label>
                    <input type="text" name="nama_di_kartu" class="form-control" placeholder="Masukkan nama sesuai kartu">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix.'kesejahteraan-siswa.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection
