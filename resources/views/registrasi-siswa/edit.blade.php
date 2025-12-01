@extends('layouts.app')

@section('content')

@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $role = $user->role;
    $prefix = $role === 'admin' ? 'admin.' : ($role === 'ptk' ? 'ptk.' : 'siswa.');

    $isEdit = isset($data->id);
@endphp

<div class="container">
    <h2 class="mb-4">
        {{ $isEdit ? 'Edit Registrasi Siswa' : 'Tambah Registrasi Siswa' }}
    </h2>

    <form
        action="{{ $isEdit ? route($prefix.'registrasi-siswa.update', $data->id) : route($prefix.'registrasi-siswa.store') }}"
        method="POST"
    >
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="row">

            <div class="col-md-6">

                <div class="mb-3">
                    <label>Peserta Didik</label>
                    <input type="text"
                           class="form-control"
                           value="{{ $data->siswa->nama_lengkap ?? '-' }}"
                           readonly>
                    <input type="hidden" name="peserta_didik_id" value="{{ $data->peserta_didik_id }}">
                </div>

                <div class="mb-3">
                    <label>Jenis Pendaftaran</label>
                    <select name="jenis_pendaftaran" class="form-control" required>
                        <option value="">Pilih Jenis</option>
                        @php
                            $jenisList = ['Siswa Baru','Pindahan','Kembali Bersekolah'];
                        @endphp
                        @foreach($jenisList as $j)
                            <option value="{{ $j }}" {{ old('jenis_pendaftaran', $data->jenis_pendaftaran) == $j ? 'selected' : '' }}>
                                {{ $j }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tanggal Masuk</label>
                    <input type="date"
                           name="tanggal_masuk"
                           class="form-control"
                           value="{{ old('tanggal_masuk', $data->tanggal_masuk) }}"
                           required>
                </div>

                <div class="mb-3">
                    <label>Sekolah Asal</label>
                    <input type="text"
                           name="sekolah_asal"
                           class="form-control"
                           value="{{ old('sekolah_asal', $data->sekolah_asal) }}">
                </div>

            </div>

            <div class="col-md-6">

                <div class="mb-3">
                    <label>No Peserta UN</label>
                    <input type="text"
                           name="no_peserta_un"
                           class="form-control"
                           value="{{ old('no_peserta_un', $data->no_peserta_un) }}">
                </div>

                <div class="mb-3">
                    <label>No Seri Ijazah</label>
                    <input type="text"
                           name="no_seri_ijazah"
                           class="form-control"
                           value="{{ old('no_seri_ijazah', $data->no_seri_ijazah) }}">
                </div>

                <div class="mb-3">
                    <label>No SKHUN</label>
                    <input type="text"
                           name="no_skhun"
                           class="form-control"
                           value="{{ old('no_skhun', $data->no_skhun) }}">
                </div>

            </div>

        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix.'registrasi-siswa.index') }}"
               class="btn btn-secondary me-2">
                Kembali
            </a>

            <button type="submit" class="btn btn-success">
                {{ $isEdit ? 'Perbarui' : 'Simpan' }}
            </button>
        </div>

    </form>
</div>

@endsection
