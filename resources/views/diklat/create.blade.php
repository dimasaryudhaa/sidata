@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Diklat PTK</h1>

    <form action="{{ route('diklat.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    @if(isset($ptkId))
                        <input type="text" class="form-control" value="{{ $ptk->nama_lengkap }}" readonly>
                        <input type="hidden" name="ptk_id" value="{{ $ptkId }}">
                    @else
                        <input type="text" class="form-control" value="{{ $ptk->nama_lengkap ?? '' }}" readonly>
                        <input type="hidden" name="ptk_id" value="{{ $ptk->id ?? '' }}">
                    @endif
                </div>

                <div class="mb-3">
                    <label>Jenis Diklat</label>
                    <input type="text" name="jenis_diklat" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Nama Diklat</label>
                    <input type="text" name="nama_diklat" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>No Sertifikat Diklat</label>
                    <input type="text" name="no_sertifikat" class="form-control" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Penyelenggara</label>
                    <input type="text" name="penyelenggara" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Tahun</label>
                    <input type="number" name="tahun" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Peran</label>
                    <select name="peran" class="form-control" required>
                        <option value="">Pilih Peran</option>
                        <option value="Pemateri">Pemateri</option>
                        <option value="Narasumber">Narasumber</option>
                        <option value="Peserta">Peserta</option>
                        <option value="Panitia">Panitia</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tingkat</label>
                    <input type="text" name="tingkat" class="form-control" required>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('diklat.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection
