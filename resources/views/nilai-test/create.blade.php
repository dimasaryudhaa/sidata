@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Nilai Test PTK</h1>

    <form action="{{ route('nilai-test.store') }}" method="POST">
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
                    <label>Jenis Test</label>
                    <input type="text" name="jenis_test" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Nama Test</label>
                    <input type="text" name="nama_test" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Penyelenggara</label>
                    <input type="text" name="penyelenggara" class="form-control" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Tahun</label>
                    <input type="number" name="tahun" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Skor</label>
                    <input type="number" step="0.01" name="skor" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Nomor Peserta</label>
                    <input type="text" name="nomor_peserta" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('nilai-test.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection
