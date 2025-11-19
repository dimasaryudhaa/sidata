@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4">{{ $anak->id ? 'Edit Anak PTK' : 'Tambah Anak PTK' }}</h1>

    @php
        $prefix = $isAdmin ? 'admin' : 'ptk';
    @endphp

    <form action="{{ $anak->id
            ? route($prefix . '.anak-ptk.update', $anak->id)
            : route($prefix . '.anak-ptk.store')
        }}" method="POST">
        @csrf
        @if($anak->id)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <input type="text" class="form-control" value="{{ $anak->ptk->nama_lengkap ?? '' }}" readonly>
                    <input type="hidden" name="ptk_id" value="{{ $anak->ptk_id }}">
                </div>

                <div class="mb-3">
                    <label>Nama Anak</label>
                    <input type="text" name="nama_anak" value="{{ $anak->nama_anak }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Status Anak</label>
                    <input type="text" name="status_anak" value="{{ $anak->status_anak }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Jenjang Pendidikan</label>
                    <input type="text" name="jenjang" value="{{ $anak->jenjang }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>NISN</label>
                    <input type="text" name="nisn" value="{{ $anak->nisn }}" class="form-control">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki" {{ $anak->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $anak->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ $anak->tempat_lahir }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ $anak->tanggal_lahir }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Tahun Masuk</label>
                    <input type="number" name="tahun_masuk" value="{{ $anak->tahun_masuk }}" class="form-control" min="1900" max="{{ date('Y') }}">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix . '.anak-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">{{ $anak->id ? 'Perbarui' : 'Simpan' }}</button>
        </div>
    </form>

</div>
@endsection
