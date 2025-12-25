@extends('layouts.app')

@section('content')

@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    $isSiswa = $user->role === 'siswa';
    $prefix = $isAdmin ? 'admin.' : 'siswa.';
@endphp

<div class="container">
    <h1>Tambah Prestasi Siswa</h1>

    <form action="{{ route($prefix . 'prestasi.store') }}" method="POST">
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
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswas as $s)
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
                    <label>Jenis Prestasi</label>
                    <select name="jenis_prestasi" class="form-control" required>
                        <option value="">Pilih Jenis</option>
                        <option value="Sains">Sains</option>
                        <option value="Seni">Seni</option>
                        <option value="Olahraga">Olahraga</option>
                        <option value="Lain-lain">Lain-lain</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tingkat Prestasi</label>
                    <select name="tingkat_prestasi" class="form-control" required>
                        <option value="">Pilih Tingkat</option>
                        @foreach(['Sekolah','Kecamatan','Kabupaten','Provinsi','Nasional','Internasional'] as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama Prestasi</label>
                    <input type="text" name="nama_prestasi" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Tahun Prestasi</label>
                    <input type="number" name="tahun_prestasi" class="form-control" placeholder="YYYY" required>
                </div>

                <div class="mb-3">
                    <label>Penyelenggara</label>
                    <input type="text" name="penyelenggara" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Peringkat</label>
                    <input type="number" name="peringkat" class="form-control">
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix.'prestasi.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>

    </form>
</div>

@endsection
