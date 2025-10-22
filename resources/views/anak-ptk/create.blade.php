@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ isset($anakPtk) ? 'Edit Anak PTK' : 'Tambah Anak PTK' }}</h1>

    <form action="{{ isset($anakPtk) ? route('anak-ptk.update', $anakPtk->id) : route('anak-ptk.store') }}" method="POST">
        @csrf
        @if(isset($anakPtk))
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    @if(isset($ptkId))
                        <input type="text" class="form-control" value="{{ $ptk->nama_lengkap }}" onlyread>
                        <input type="hidden" name="ptk_id" value="{{ $ptkId }}">
                    @else
                        <select name="ptk_id" class="form-control" required>
                            <option value="">Pilih PTK</option>
                            @foreach($ptks as $p)
                                <option value="{{ $p->id }}" {{ (old('ptk_id', $anakPtk->ptk_id ?? '') == $p->id) ? 'selected' : '' }}>
                                    {{ $p->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="mb-3">
                    <label>Nama Anak</label>
                    <input type="text" name="nama_anak" class="form-control"
                           value="{{ old('nama_anak', $anakPtk->nama_anak ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label>Status Anak</label>
                    <input type="text" name="status_anak" class="form-control"
                           value="{{ old('status_anak', $anakPtk->status_anak ?? '') }}"
                           placeholder="Contoh: Anak Kandung, Anak Angkat" required>
                </div>

                <div class="mb-3">
                    <label>Jenjang Pendidikan</label>
                    <input type="text" name="jenjang" class="form-control"
                    value="{{ old('jenjang', $anakPtk->jenjang ?? '') }}"
                    placeholder="Contoh: SD, SMP, SMA" required>
                </div>
                <div class="mb-3">
                    <label>NISN</label>
                    <input type="text" name="nisn" class="form-control"
                    value="{{ old('nisn', $anakPtk->nisn ?? '') }}">
                </div>
            </div>

            <div class="col-md-6">

                <div class="mb-3">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin', $anakPtk->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $anakPtk->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control"
                           value="{{ old('tempat_lahir', $anakPtk->tempat_lahir ?? '') }}">
                </div>

                <div class="mb-3">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control"
                           value="{{ old('tanggal_lahir', isset($anakPtk) ? $anakPtk->tanggal_lahir->format('Y-m-d') : '') }}">
                </div>

                <div class="mb-3">
                    <label>Tahun Masuk</label>
                    <input type="number" name="tahun_masuk" class="form-control" min="1900" max="{{ date('Y') }}"
                           value="{{ old('tahun_masuk', $anakPtk->tahun_masuk ?? '') }}">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('anak-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">{{ isset($anakPtk) ? 'Update' : 'Simpan' }}</button>
        </div>
    </form>
</div>
@endsection
