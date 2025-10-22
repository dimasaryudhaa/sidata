@extends('layouts.app')

@section('content')

<div class="container">
    <h3>Tambah Tunjangan PTK</h3>
    <form action="{{ route('tunjangan.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    @if(isset($ptk))
                        <input type="text" class="form-control" value="{{ $ptk->nama_lengkap }}" readonly>
                        <input type="hidden" name="ptk_id" value="{{ $ptk->id }}">
                    @else
                        <select name="ptk_id" class="form-control" required>
                            <option value="">-- Pilih PTK --</option>
                            @foreach($ptks as $ptk)
                                <option value="{{ $ptk->id }}">{{ $ptk->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="mb-3">
                    <label>Jenis Tunjangan</label>
                    <input type="text" name="jenis_tunjangan" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Nama Tunjangan</label>
                    <input type="text" name="nama_tunjangan" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Instansi</label>
                    <input type="text" name="instansi" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Nomor SK Tunjangan</label>
                    <input type="text" name="sk_tunjangan" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Tanggal SK Tunjangan</label>
                    <input type="date" name="tgl_sk_tunjangan" class="form-control">
                </div>

            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Semester</label>
                    <select name="semester_id" class="form-control" required>
                        <option value="">Pilih Semester</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}">{{ $semester->nama_semester }} - {{ $semester->tahun_ajaran }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Sumber Dana</label>
                    <input type="text" name="sumber_dana" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Dari Tahun</label>
                    <input type="number" name="dari_tahun" class="form-control" min="1900" max="2100">
                </div>

                <div class="mb-3">
                    <label>Sampai Tahun</label>
                    <input type="number" name="sampai_tahun" class="form-control" min="1900" max="2100">
                </div>

                <div class="mb-3">
                    <label>Nominal (Rp)</label>
                    <input type="number" name="nominal" class="form-control" min="0" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="Masih Menerima">Masih Menerima</option>
                        <option value="Tidak Menerima">Tidak Menerima</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('tunjangan.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>

@endsection
