@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Diklat PTK</h1>

    <form action="{{ route('diklat.update', $diklat->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <input type="text" class="form-control" value="{{ $ptk->nama_lengkap ?? $diklat->ptk->nama_lengkap }}" readonly>
                    <input type="hidden" name="ptk_id" value="{{ $ptk->id ?? $diklat->ptk_id }}">
                </div>

                <div class="mb-3">
                    <label>Jenis Diklat</label>
                    <input type="text" name="jenis_diklat" class="form-control" value="{{ $diklat->jenis_diklat }}" required>
                </div>

                <div class="mb-3">
                    <label>Nama Diklat</label>
                    <input type="text" name="nama_diklat" class="form-control" value="{{ $diklat->nama_diklat }}" required>
                </div>

                <div class="mb-3">
                    <label>No Sertifikat Diklat</label>
                    <input type="text" name="no_sertifikat" class="form-control" value="{{ $diklat->no_sertifikat }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Penyelenggara</label>
                    <input type="text" name="penyelenggara" class="form-control" value="{{ $diklat->penyelenggara }}" required>
                </div>
                <div class="mb-3">
                    <label>Tahun</label>
                    <input type="number" name="tahun" class="form-control" value="{{ $diklat->tahun }}" required>
                </div>

                <div class="mb-3">
                    <label>Peran</label>
                    <select name="peran" class="form-control" required>
                        <option value="">-- Pilih Peran --</option>
                        <option value="Pemateri" {{ $diklat->peran == 'Pemateri' ? 'selected' : '' }}>Pemateri</option>
                        <option value="Narasumber" {{ $diklat->peran == 'Narasumber' ? 'selected' : '' }}>Narasumber</option>
                        <option value="Peserta" {{ $diklat->peran == 'Peserta' ? 'selected' : '' }}>Peserta</option>
                        <option value="Panitia" {{ $diklat->peran == 'Panitia' ? 'selected' : '' }}>Panitia</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tingkat</label>
                    <input type="text" name="tingkat" class="form-control" value="{{ $diklat->tingkat }}" placeholder="Contoh: Nasional, Provinsi" required>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('diklat.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>
    </form>
</div>
@endsection
