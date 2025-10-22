@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Registrasi Siswa</h1>

    <form
        action="{{ $data->id ? route('registrasi-siswa.update', $data->id) : route('registrasi-siswa.store') }}"
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
                    <label>Jenis Pendaftaran</label>
                    <select name="jenis_pendaftaran" class="form-control" required>
                        <option value="Siswa Baru" {{ $data->jenis_pendaftaran == 'Siswa Baru' ? 'selected' : '' }}>Siswa Baru</option>
                        <option value="Pindahan" {{ $data->jenis_pendaftaran == 'Pindahan' ? 'selected' : '' }}>Pindahan</option>
                        <option value="Kembali Bersekolah" {{ $data->jenis_pendaftaran == 'Kembali Bersekolah' ? 'selected' : '' }}>Kembali Bersekolah</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" value="{{ $data->tanggal_masuk }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Sekolah Asal</label>
                    <input type="text" name="sekolah_asal" value="{{ $data->sekolah_asal }}" class="form-control">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>No Peserta UN</label>
                    <input type="text" name="no_peserta_un" value="{{ $data->no_peserta_un }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>No Seri Ijazah</label>
                    <input type="text" name="no_seri_ijazah" value="{{ $data->no_seri_ijazah }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>No SKHUN</label>
                    <input type="text" name="no_skhun" value="{{ $data->no_skhun }}" class="form-control">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('registrasi-siswa.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">
                {{ $data->id ? 'Update' : 'Simpan' }}
            </button>
        </div>
    </form>
</div>
@endsection
