@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Kontak PTK</h1>

    <form action="{{ route('kontak-ptk.store') }}" method="POST">
        @csrf
        <div class="row">

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <select name="ptk_id" class="form-control" required>
                        <option value="">Pilih PTK</option>
                        @foreach($ptk as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>No HP</label>
                    <input type="text" name="no_hp" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Alamat Jalan</label>
                    <input type="text" name="alamat_jalan" class="form-control">
                </div>
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>RT</label>
                        <input type="text" name="rt" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>RW</label>
                        <input type="text" name="rw" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label>Kelurahan</label>
                    <input type="text" name="kelurahan" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Kecamatan</label>
                    <input type="text" name="kecamatan" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Kode Pos</label>
                    <input type="text" name="kode_pos" class="form-control">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <a href="{{ route('kontak-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection
