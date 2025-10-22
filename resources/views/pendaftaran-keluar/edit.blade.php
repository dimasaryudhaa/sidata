@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Pendaftaran Keluar Siswa</h1>

    <form action="{{ route('pendaftaran-keluar.update', $pendaftaranKeluar->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama Peserta Didik</label>
                    <select name="peserta_didik_id" class="form-control" required>
                        <option value="">Pilih Peserta Didik</option>
                        @foreach($siswa as $s)
                            <option value="{{ $s->id }}" {{ $pendaftaranKeluar->peserta_didik_id == $s->id ? 'selected' : '' }}>
                                {{ $s->nama_lengkap ?? $s->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>PTK</label>
                    <select name="ptk_id" class="form-control" required>
                        <option value="">Pilih PTK</option>
                        @foreach($ptk as $p)
                            <option value="{{ $p->id }}" {{ $pendaftaranKeluar->ptk_id == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Orang Tua</label>
                    <select name="orang_tua_id" class="form-control" required>
                        <option value="">Pilih Orang Tua</option>
                        @foreach($orangTua as $ot)
                            <option value="{{ $ot->id }}" {{ $pendaftaranKeluar->orang_tua_id == $ot->id ? 'selected' : '' }}>
                                {{ $ot->nama }}
                            </option>
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
                        @foreach(['Mutasi','Dikeluarkan','Mengundurkan Diri','Lulus','Wafat','Hilang','Lainnya'] as $alasan)
                            <option value="{{ $alasan }}" {{ $pendaftaranKeluar->keluar_karena == $alasan ? 'selected' : '' }}>
                                {{ $alasan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tanggal Keluar</label>
                    <input type="date" name="tanggal_keluar" class="form-control" value="{{ $pendaftaranKeluar->tanggal_keluar }}" required>
                </div>

                <div class="mb-3">
                    <label>Alasan</label>
                    <textarea name="alasan" class="form-control" rows="3">{{ $pendaftaranKeluar->alasan }}</textarea>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <a href="{{ route('pendaftaran-keluar.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Update</button>
        </div>
    </form>
</div>
@endsection
