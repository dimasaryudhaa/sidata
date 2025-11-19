@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ $data->id ? 'Edit Kontak PTK' : 'Tambah Kontak PTK' }}</h1>

    @php
        $user = Auth::user();
        $prefix = $user->role === 'ptk' ? 'ptk.' : 'admin.';
    @endphp

    <form action="{{ $data->id
            ? route($prefix.'kontak-ptk.update', $data->id)
            : route($prefix.'kontak-ptk.store')
        }}" method="POST">
        @csrf
        @if($data->id)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <input type="text" class="form-control" value="{{ $data->ptk->nama_lengkap ?? '' }}" readonly>
                    <input type="hidden" name="ptk_id" value="{{ $data->ptk_id }}">
                </div>

                <div class="mb-3">
                    <label>No HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ $data->no_hp }}">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $data->email }}">
                </div>

                <div class="mb-3">
                    <label>Alamat Jalan</label>
                    <input type="text" name="alamat_jalan" class="form-control" value="{{ $data->alamat_jalan }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>RT</label>
                        <input type="text" name="rt" class="form-control" value="{{ $data->rt }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>RW</label>
                        <input type="text" name="rw" class="form-control" value="{{ $data->rw }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label>Kelurahan</label>
                    <input type="text" name="kelurahan" class="form-control" value="{{ $data->kelurahan }}">
                </div>

                <div class="mb-3">
                    <label>Kecamatan</label>
                    <input type="text" name="kecamatan" class="form-control" value="{{ $data->kecamatan }}">
                </div>

                <div class="mb-3">
                    <label>Kode Pos</label>
                    <input type="text" name="kode_pos" class="form-control" value="{{ $data->kode_pos }}">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix.'kontak-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">
                {{ $data->id ? 'Perbarui' : 'Simpan' }}
            </button>
        </div>
    </form>
</div>
@endsection
