@extends('layouts.app')

@section('content')
<div class="container">

    @php
        $prefix = isset($isAdmin) && $isAdmin ? 'admin' : 'ptk';
        $isEdit = isset($ptk);
    @endphp

    <h1 class="mb-4">{{ $isEdit ? 'Edit PTK' : 'Tambah PTK' }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $isEdit ? route($prefix . '.ptk.update', $ptk->id) : route($prefix . '.ptk.store') }}" method="POST">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror"
                        value="{{ old('nama_lengkap', $ptk->nama_lengkap ?? '') }}" {{ $isEdit ? 'readonly' : '' }} required>
                    @error('nama_lengkap')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>NIK</label>
                    <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror"
                        value="{{ old('nik', $ptk->nik ?? '') }}" required>
                    @error('nik')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin', $ptk->jenis_kelamin ?? '')=='Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $ptk->jenis_kelamin ?? '')=='Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror"
                        value="{{ old('tempat_lahir', $ptk->tempat_lahir ?? '') }}" required>
                    @error('tempat_lahir')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                        value="{{ old('tanggal_lahir', $ptk->tanggal_lahir ?? '') }}" required>
                    @error('tanggal_lahir')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Nama Ibu Kandung</label>
                    <input type="text" name="nama_ibu_kandung" class="form-control @error('nama_ibu_kandung') is-invalid @enderror"
                        value="{{ old('nama_ibu_kandung', $ptk->nama_ibu_kandung ?? '') }}" required>
                    @error('nama_ibu_kandung')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Agama</label>
                    <select name="agama" class="form-control @error('agama') is-invalid @enderror">
                        <option value="">Pilih Agama</option>
                        @php
                            $agamas = ['Islam','Kristen/Protestan','Katholik','Hindu','Budha','Konghucu'];
                        @endphp
                        @foreach($agamas as $agama)
                            <option value="{{ $agama }}" {{ old('agama', $ptk->agama ?? '')==$agama ? 'selected' : '' }}>{{ $agama }}</option>
                        @endforeach
                    </select>
                    @error('agama')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>NPWP</label>
                    <input type="text" name="npwp" class="form-control @error('npwp') is-invalid @enderror"
                        value="{{ old('npwp', $ptk->npwp ?? '') }}">
                    @error('npwp')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Nama Wajib Pajak</label>
                    <input type="text" name="nama_wajib_pajak" class="form-control @error('nama_wajib_pajak') is-invalid @enderror"
                        value="{{ old('nama_wajib_pajak', $ptk->nama_wajib_pajak ?? '') }}">
                    @error('nama_wajib_pajak')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Kewarganegaraan</label>
                    <select name="kewarganegaraan" class="form-control @error('kewarganegaraan') is-invalid @enderror">
                        <option value="Indonesia (WNI)" {{ old('kewarganegaraan', $ptk->kewarganegaraan ?? '')=='Indonesia (WNI)' ? 'selected' : '' }}>Indonesia (WNI)</option>
                        <option value="Asing (WNA)" {{ old('kewarganegaraan', $ptk->kewarganegaraan ?? '')=='Asing (WNA)' ? 'selected' : '' }}>Asing (WNA)</option>
                    </select>
                    @error('kewarganegaraan')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Negara Asal</label>
                    <input type="text" name="negara_asal" class="form-control @error('negara_asal') is-invalid @enderror"
                        value="{{ old('negara_asal', $ptk->negara_asal ?? '') }}" placeholder="Isi jika WNA">
                    @error('negara_asal')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('admin.ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">{{ $isEdit ? 'Perbarui' : 'Simpan' }}</button>
        </div>
    </form>
</div>
@endsection
