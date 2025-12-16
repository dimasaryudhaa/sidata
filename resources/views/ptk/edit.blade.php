@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4">Edit PTK</h1>

    @php
        $prefix = $isAdmin ? 'admin' : 'ptk';
    @endphp

    <form action="{{ route($prefix . '.ptk.update', $ptk->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">

                <div class="mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="{{ $ptk->nama_lengkap }}" required>
                </div>

                <div class="mb-3">
                    <label>Jenis PTK</label>
                    <select name="jenis_ptk" class="form-control">
                        <option value="">Pilih Jenis PTK</option>
                        <option value="Guru" {{ $ptk->jenis_ptk=='Guru' ? 'selected' : '' }}>Guru</option>
                        <option value="Staf" {{ $ptk->jenis_ptk=='Staf' ? 'selected' : '' }}>Staf</option>
                        <option value="Laboran" {{ $ptk->jenis_ptk=='Laboran' ? 'selected' : '' }}>Laboran</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>NIK</label>
                    <input type="text" name="nik" class="form-control" value="{{ $ptk->nik }}">
                </div>

                <div class="mb-3">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ $ptk->jenis_kelamin=='Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $ptk->jenis_kelamin=='Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control" value="{{ $ptk->tempat_lahir }}">
                </div>

                <div class="mb-3">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ $ptk->tanggal_lahir }}">
                </div>

                <div class="mb-3">
                    <label>Nama Ibu Kandung</label>
                    <input type="text" name="nama_ibu_kandung" class="form-control" value="{{ $ptk->nama_ibu_kandung }}">
                </div>

            </div>

            <div class="col-md-6">

                <div class="mb-3">
                    <label>Agama</label>
                    <select name="agama" class="form-control">
                        <option value="">Pilih Agama</option>
                        <option value="Islam" {{ $ptk->agama=='Islam' ? 'selected' : '' }}>Islam</option>
                        <option value="Kristen/Protestan" {{ $ptk->agama=='Kristen/Protestan' ? 'selected' : '' }}>Kristen/Protestan</option>
                        <option value="Katholik" {{ $ptk->agama=='Katholik' ? 'selected' : '' }}>Katholik</option>
                        <option value="Hindu" {{ $ptk->agama=='Hindu' ? 'selected' : '' }}>Hindu</option>
                        <option value="Buddha" {{ $ptk->agama=='Buddha' ? 'selected' : '' }}>Buddha</option>
                        <option value="Konghucu" {{ $ptk->agama=='Konghucu' ? 'selected' : '' }}>Konghucu</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>NPWP</label>
                    <input type="text" name="npwp" class="form-control" value="{{ $ptk->npwp }}">
                </div>

                <div class="mb-3">
                    <label>Nama Wajib Pajak</label>
                    <input type="text" name="nama_wajib_pajak" class="form-control" value="{{ $ptk->nama_wajib_pajak }}">
                </div>

                <div class="mb-3">
                    <label>Kewarganegaraan</label>
                    <select name="kewarganegaraan" class="form-control">
                        <option value="Indonesia (WNI)" {{ $ptk->kewarganegaraan=='Indonesia (WNI)' ? 'selected' : '' }}>Indonesia (WNI)</option>
                        <option value="Asing (WNA)" {{ $ptk->kewarganegaraan=='Asing (WNA)' ? 'selected' : '' }}>Asing (WNA)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Negara Asal</label>
                    <input type="text" name="negara_asal" class="form-control" value="{{ $ptk->negara_asal }}" placeholder="Isi jika WNA">
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix . '.ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>

    </form>

</div>
@endsection
