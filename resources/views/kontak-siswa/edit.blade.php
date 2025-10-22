@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Kontak Siswa</h1>

    <form
        action="{{ $data->id ? route('kontak-siswa.update', $data->id) : route('kontak-siswa.store') }}"
        method="POST"
    >
        @csrf
        @if($data->id)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Peserta Didik</label>
                    <input type="text" class="form-control" value="{{ $data->siswa->nama_lengkap ?? '-' }}" readonly>
                    <input type="hidden" name="peserta_didik_id" value="{{ $data->peserta_didik_id }}">
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

                <div class="mb-3">
                    <label>RT</label>
                    <input type="text" name="rt" class="form-control" value="{{ $data->rt }}">
                </div>

                <div class="mb-3">
                    <label>RW</label>
                    <input type="text" name="rw" class="form-control" value="{{ $data->rw }}">
                </div>
            </div>

            <div class="col-md-6">
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

                <div class="mb-3">
                    <label>Tempat Tinggal</label>
                    <select name="tempat_tinggal" class="form-control">
                        <option value="">Pilih Tempat Tinggal</option>
                        @foreach(['Bersama Orang Tua','Kos','Asrama','Lainnya'] as $option)
                            <option value="{{ $option }}" {{ $data->tempat_tinggal == $option ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Moda Transportasi</label>
                    <input type="text" name="moda_transportasi" class="form-control" value="{{ $data->moda_transportasi }}">
                </div>

                <div class="mb-3">
                    <label>Anak Ke</label>
                    <input type="number" name="anak_ke" class="form-control" value="{{ $data->anak_ke }}">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('kontak-siswa.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">
                {{ $data->id ? 'Perbarui' : 'Simpan' }}
            </button>
        </div>
    </form>
</div>
@endsection
