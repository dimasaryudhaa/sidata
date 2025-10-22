@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Pendaftaran Keluar Siswa</h1>

    <form action="{{ route('pendaftaran-keluar.store') }}" method="POST">
        @csrf

        <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama Peserta Didik</label>
                    <select name="peserta_didik_id" class="form-control" required>
                        <option value="">Pilih Peserta Didik</option>
                        @foreach($siswa as $s)
                            <option value="{{ $s->id }}">{{ $s->nama_lengkap ?? $s->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>PTK</label>
                    <select name="ptk_id" class="form-control" required>
                        <option value="">Pilih PTK</option>
                        @foreach($ptk as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Nama Ayah</label>
                    <select name="orang_tua_id" class="form-control" required>
                        <option value="">Pilih Nama Ayah</option>
                        @foreach($orangTua as $ot)
                            <option value="{{ $ot->id }}">{{ $ot->nama_ayah }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Keluar Karena</label>
                    <select name="keluar_karena" class="form-control" required>
                        <option value="">Pilih Alasan Keluar</option>
                        <option value="Mutasi">Mutasi</option>
                        <option value="Dikeluarkan">Dikeluarkan</option>
                        <option value="Mengundurkan Diri">Mengundurkan Diri</option>
                        <option value="Lulus">Lulus</option>
                        <option value="Wafat">Wafat</option>
                        <option value="Hilang">Hilang</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tanggal Keluar</label>
                    <input type="date" name="tanggal_keluar" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Alasan</label>
                    <textarea name="alasan" class="form-control" rows="3" placeholder="Tuliskan alasan keluar siswa..."></textarea>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <a href="{{ route('pendaftaran-keluar.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection
