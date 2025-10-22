@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Penugasan PTK</h1>

    <form action="{{ route('penugasan-ptk.store') }}" method="POST">
        @csrf

        <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label>PTK</label>
                    <select name="ptk_id" class="form-control" required>
                        <option value="">Pilih PTK</option>
                        @foreach($ptks as $ptk)
                            <option value="{{ $ptk->id }}">{{ $ptk->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Nomor Surat Tugas</label>
                    <input type="text" name="nomor_surat_tugas" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Tanggal Surat Tugas</label>
                    <input type="date" name="tanggal_surat_tugas" class="form-control" required>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label>TMT Tugas</label>
                    <input type="date" name="tmt_tugas" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Status Sekolah Induk</label>
                    <select name="status_sekolah_induk" class="form-control" required>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <a href="{{ route('penugasan-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection
    