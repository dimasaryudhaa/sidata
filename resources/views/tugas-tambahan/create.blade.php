@extends('layouts.app')

@section('content')

<div class="container">
    <h3>Tambah Tugas Tambahan</h3>
    <form action="{{ route('tugas-tambahan.store') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Kolom Kiri -->
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
                    <label>Jabatan PTK</label>
                    <input type="text" name="jabatan_ptk" class="form-control" placeholder="Contoh: Kepala Perpustakaan" required>
                </div>

                <div class="mb-3">
                    <label>Prasarana</label>
                    <input type="text" name="prasarana" class="form-control" placeholder="Isi jika Kepala Perpustakaan/Lab/Bengkel">
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nomor SK</label>
                    <input type="text" name="nomor_sk" class="form-control" placeholder="Minimal 10 digit" required>
                </div>

                <div class="mb-3">
                    <label>TMT Tambahan</label>
                    <input type="date" name="tmt_tambahan" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>TST Tambahan</label>
                    <input type="date" name="tst_tambahan" class="form-control" placeholder="Kosongkan jika masih menjabat">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('tugas-tambahan.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>

@endsection
