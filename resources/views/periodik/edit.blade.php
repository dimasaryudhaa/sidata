@extends('layouts.app')

@section('content')

@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    $isSiswa = $user->role === 'siswa';
    $prefix = $isAdmin ? 'admin' : 'siswa';
@endphp

<div class="container">
    <h1 class="mb-4">{{ $periodik->id ? 'Edit Data Periodik Siswa' : 'Tambah Data Periodik Siswa' }}</h1>

    <form action="{{ $periodik->id
            ? route($prefix . '.periodik.update', $periodik->id)
            : route($prefix . '.periodik.store')
        }}"
        method="POST">

        @csrf
        @if($periodik->id)
            @method('PUT')
        @endif

        <div class="row">

            {{-- KOLOM KIRI --}}
            <div class="col-md-6">

                <div class="mb-3">
                    <label>Nama Siswa</label>
                    <input type="text" class="form-control"
                        value="{{ $siswa->firstWhere('id', $periodik->peserta_didik_id)?->nama_lengkap }}"
                        readonly>

                    <input type="hidden" name="peserta_didik_id" value="{{ $periodik->peserta_didik_id }}">
                </div>

                <div class="mb-3">
                    <label>Tinggi Badan (cm)</label>
                    <input type="number" step="1" name="tinggi_badan_cm" class="form-control"
                        value="{{ old('tinggi_badan_cm', intval($periodik->tinggi_badan_cm)) }}">
                </div>

                <div class="mb-3">
                    <label>Berat Badan (kg)</label>
                    <input type="number" step="1" name="berat_badan_kg" class="form-control"
                        value="{{ old('berat_badan_kg', intval($periodik->berat_badan_kg)) }}">
                </div>

                <div class="mb-3">
                    <label>Lingkar Kepala (cm)</label>
                    <input type="number" step="1" name="lingkar_kepala_cm" class="form-control"
                        value="{{ old('lingkar_kepala_cm', intval($periodik->lingkar_kepala_cm)) }}">
                </div>

                <div class="mb-3">
                    <label>Jumlah Saudara</label>
                    <input type="number" name="jumlah_saudara" class="form-control"
                        value="{{ old('jumlah_saudara', $periodik->jumlah_saudara) }}">
                </div>

            </div>

            {{-- KOLOM KANAN --}}
            <div class="col-md-6">

                <div class="mb-3">
                    <label>Jarak ke Sekolah</label>
                    <select name="jarak_ke_sekolah" class="form-control">
                        <option value="">Pilih</option>
                        <option value="Kurang dari 1 km"
                            {{ $periodik->jarak_ke_sekolah == 'Kurang dari 1 km' ? 'selected' : '' }}>
                            Kurang dari 1 km
                        </option>

                        <option value="Lebih dari 1 km"
                            {{ $periodik->jarak_ke_sekolah == 'Lebih dari 1 km' ? 'selected' : '' }}>
                            Lebih dari 1 km
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Jarak Sebenarnya (km)</label>
                    <input type="number" step="0.1" name="jarak_sebenarnya_km" class="form-control"
                        value="{{ old('jarak_sebenarnya_km', intval($periodik->jarak_sebenarnya_km)) }}">
                </div>

                <div class="mb-3">
                    <label>Waktu Tempuh (Jam)</label>
                    <input type="number" name="waktu_tempuh_jam" class="form-control"
                        value="{{ old('waktu_tempuh_jam', $periodik->waktu_tempuh_jam) }}">
                </div>

                <div class="mb-3">
                    <label>Waktu Tempuh (Menit)</label>
                    <input type="number" name="waktu_tempuh_menit" class="form-control"
                        value="{{ old('waktu_tempuh_menit', $periodik->waktu_tempuh_menit) }}">
                </div>

            </div>

        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix . '.periodik.index') }}" class="btn btn-secondary me-2">Kembali</a>

            <button type="submit" class="btn btn-success">
                {{ $periodik->id ? 'Perbarui' : 'Simpan' }}
            </button>
        </div>

    </form>

</div>

@endsection
