@extends('layouts.app')

@section('content')
<div class="container">

    @php
        $prefix = isset($isAdmin) && $isAdmin ? 'admin' : 'ptk';
        $isEdit = isset($ptk);
    @endphp

    <h1 class="mb-4">{{ $isEdit ? 'Edit PTK' : 'Tambah PTK' }}</h1>

    <form action="{{ $isEdit ? route($prefix . '.ptk.update', $ptk->id) : route($prefix . '.ptk.store') }}" method="POST">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="{{ $ptk->nama_lengkap ?? '' }}" {{ $isEdit ? 'readonly' : '' }} required>
                </div>

                <div class="mb-3">
                    <label>NIK</label>
                    <input type="text" name="nik" class="form-control" value="{{ $ptk->nik ?? '' }}" required>
                </div>

                <div class="mb-3">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ (isset($ptk) && $ptk->jenis_kelamin=='Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ (isset($ptk) && $ptk->jenis_kelamin=='Perempuan') ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control" value="{{ $ptk->tempat_lahir ?? '' }}" required>
                </div>

                <div class="mb-3">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ $ptk->tanggal_lahir ?? '' }}" required>
                </div>

                <div class="mb-3">
                    <label>Nama Ibu Kandung</label>
                    <input type="text" name="nama_ibu_kandung" class="form-control" value="{{ $ptk->nama_ibu_kandung ?? '' }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Agama</label>
                    <select name="agama" class="form-control">
                        <option value="">Pilih Agama</option>
                        @php
                            $agamas = ['Islam','Kristen/Protestan','Katholik','Hindu','Buddha','Konghucu'];
                        @endphp
                        @foreach($agamas as $agama)
                            <option value="{{ $agama }}" {{ (isset($ptk) && $ptk->agama==$agama) ? 'selected' : '' }}>{{ $agama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>NPWP</label>
                    <input type="text" name="npwp" class="form-control" value="{{ $ptk->npwp ?? '' }}">
                </div>

                <div class="mb-3">
                    <label>Nama Wajib Pajak</label>
                    <input type="text" name="nama_wajib_pajak" class="form-control" value="{{ $ptk->nama_wajib_pajak ?? '' }}">
                </div>

                <div class="mb-3">
                    <label>Kewarganegaraan</label>
                    <select name="kewarganegaraan" class="form-control">
                        <option value="Indonesia (WNI)" {{ (isset($ptk) && $ptk->kewarganegaraan=='Indonesia (WNI)') ? 'selected' : '' }}>Indonesia (WNI)</option>
                        <option value="Asing (WNA)" {{ (isset($ptk) && $ptk->kewarganegaraan=='Asing (WNA)') ? 'selected' : '' }}>Asing (WNA)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Negara Asal</label>
                    <input type="text" name="negara_asal" class="form-control" value="{{ $ptk->negara_asal ?? '' }}" placeholder="Isi jika WNA">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix . '.ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">{{ $isEdit ? 'Perbarui' : 'Simpan' }}</button>
        </div>
    </form>
</div>
@endsection
