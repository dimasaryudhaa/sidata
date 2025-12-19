@extends('layouts.app')

@section('content')

@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $role = $user->role;
    $prefix = $role === 'admin' ? 'admin.' : ($role === 'ptk' ? 'ptk.' : 'siswa.');
    $isEdit = isset($siswa) && isset($siswa->id);
@endphp

<div class="container">
    <h1 class="mb-4">{{ $isEdit ? 'Edit Peserta Didik' : 'Tambah Peserta Didik' }}</h1>

    <form
        action="{{ $isEdit
            ? route($prefix.'siswa.update', $siswa->id)
            : route($prefix.'siswa.store')
        }}"
        method="POST">

        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-6">

                <div class="mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap"
                        value="{{ old('nama_lengkap', $siswa->nama_lengkap ?? '') }}"
                        class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>NIS</label>
                    <input type="text" name="nis"
                        value="{{ old('nis', $siswa->nis ?? '') }}"
                        class="form-control">
                </div>

                <div class="mb-3">
                    <label>NISN</label>
                    <input type="text" name="nisn"
                        value="{{ old('nisn', $siswa->nisn ?? '') }}"
                        class="form-control">
                </div>

                <div class="mb-3">
                    <label>NIK</label>
                    <input type="text" name="nik"
                        value="{{ old('nik', $siswa->nik ?? '') }}"
                        class="form-control">
                </div>

                <div class="mb-3">
                    <label>No KK</label>
                    <input type="text" name="no_kk"
                        value="{{ old('no_kk', $siswa->no_kk ?? '') }}"
                        class="form-control">
                </div>

                <div class="mb-3">
                    <label>Rombel</label>
                    <select name="rombel_id" class="form-control">
                        <option value="">Pilih Rombel</option>
                        @foreach($rombels as $rombel)
                            <option value="{{ $rombel->id }}"
                                {{ old('rombel_id', $siswa->rombel_id ?? '') == $rombel->id ? 'selected' : '' }}>
                                {{ $rombel->nama_rombel }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="col-md-6">

                <div class="mb-3">
                    <label>Tempat Lahir</label>
                    <input type="text" name="tempat_lahir"
                        value="{{ old('tempat_lahir', $siswa->tempat_lahir ?? '') }}"
                        class="form-control">
                </div>

                <div class="mb-3">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir"
                        value="{{ old('tanggal_lahir', $siswa->tanggal_lahir ?? '') }}"
                        class="form-control">
                </div>

                <div class="mb-3">
                    <label>Agama</label>
                    <select name="agama" class="form-control">
                        <option value="">Pilih Agama</option>
                        @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $agama)
                            <option value="{{ $agama }}"
                                {{ old('agama', $siswa->agama ?? '') == $agama ? 'selected' : '' }}>
                                {{ $agama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Rayon</label>
                    <select name="rayon_id" class="form-control">
                        <option value="">Pilih Rayon</option>
                        @foreach($rayons as $rayon)
                            <option value="{{ $rayon->id }}"
                                {{ old('rayon_id', $siswa->rayon_id ?? '') == $rayon->id ? 'selected' : '' }}>
                                {{ $rayon->nama_rayon }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Kewarganegaraan</label>
                    <select name="kewarganegaraan" id="kewarganegaraan" class="form-control">
                        <option value="">Pilih Kewarganegaraan</option>
                        <option value="WNI" {{ old('kewarganegaraan', $siswa->kewarganegaraan ?? '') == 'WNI' ? 'selected' : '' }}>WNI</option>
                        <option value="WNA" {{ old('kewarganegaraan', $siswa->kewarganegaraan ?? '') == 'WNA' ? 'selected' : '' }}>WNA</option>
                    </select>
                </div>

                <div class="mb-3" id="negaraAsalWrapper" style="display: none;">
                    <label>Negara Asal</label>
                    <input type="text" name="negara_asal" id="negara_asal"
                        value="{{ old('negara_asal', $siswa->negara_asal ?? '') }}"
                        class="form-control">
                </div>

                <div class="mb-3">
                    <label>Berkebutuhan Khusus</label>
                    <input type="text" name="berkebutuhan_khusus"
                        value="{{ old('berkebutuhan_khusus', $siswa->berkebutuhan_khusus ?? '') }}"
                        class="form-control">
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix.'siswa.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">
                {{ $isEdit ? 'Perbarui' : 'Simpan' }}
            </button>
        </div>

    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const kewarganegaraan = document.getElementById('kewarganegaraan');
        const negaraAsalWrapper = document.getElementById('negaraAsalWrapper');
        const negaraAsalInput = document.getElementById('negara_asal');

        function toggleNegaraAsal() {
            if (kewarganegaraan.value === 'WNA') {
                negaraAsalWrapper.style.display = 'block';
                negaraAsalInput.required = true;
            } else {
                negaraAsalWrapper.style.display = 'none';
                negaraAsalInput.required = false;
                negaraAsalInput.value = '';
            }
        }

        kewarganegaraan.addEventListener('change', toggleNegaraAsal);
        toggleNegaraAsal();
    });
</script>

@endsection
