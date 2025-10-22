@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Data Periodik Siswa</h1>

    <form action="{{ route('periodik.store') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Kolom Kiri: Data Fisik -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama Siswa</label>
                    <select name="peserta_didik_id" class="form-control" required>
                        <option value="">Pilih Siswa</option>
                        @foreach($siswa as $s)
                            <option value="{{ $s->id }}">{{ $s->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label>Tinggi Badan (cm)</label>
                    <input type="number" step="0.01" name="tinggi_badan_cm" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Berat Badan (kg)</label>
                    <input type="number" step="0.01" name="berat_badan_kg" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Lingkar Kepala (cm)</label>
                    <input type="number" step="0.01" name="lingkar_kepala_cm" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Jumlah Saudara</label>
                    <input type="number" name="jumlah_saudara" class="form-control">
                </div>
            </div>

            <!-- Kolom Kanan: Data Jarak dan Waktu -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Jarak ke Sekolah</label>
                    <select name="jarak_ke_sekolah" class="form-control">
                        <option value="">Pilih</option>
                        <option value="Kurang dari 1 km">Kurang dari 1 km</option>
                        <option value="Lebih dari 1 km">Lebih dari 1 km</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Jarak Sebenarnya (km)</label>
                    <input type="number" step="0.01" name="jarak_sebenarnya_km" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Waktu Tempuh (jam)</label>
                    <input type="number" name="waktu_tempuh_jam" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Waktu Tempuh (menit)</label>
                    <input type="number" name="waktu_tempuh_menit" class="form-control">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <a href="{{ route('periodik.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection
