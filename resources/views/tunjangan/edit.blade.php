@extends('layouts.app')

@section('content')

<div class="container">
    <h1 class="mb-4">{{ $tunjangan->id ? 'Edit Tunjangan PTK' : 'Tambah Tunjangan PTK' }}</h1>

    @php
        $prefix = $isAdmin ? 'admin' : 'ptk';
    @endphp

    <form action="{{ $tunjangan->id
            ? route($prefix . '.tunjangan.update', $tunjangan->id)
            : route($prefix . '.tunjangan.store')
        }}" method="POST">
        @csrf
        @if($tunjangan->id)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <input type="text" class="form-control"
                        value="{{ $ptk->nama_lengkap ?? $tunjangan->ptk->nama_lengkap }}" readonly>
                    <input type="hidden" name="ptk_id"
                        value="{{ $ptk->id ?? $tunjangan->ptk_id }}">
                </div>

                <div class="mb-3">
                    <label>Jenis Tunjangan</label>
                    <input type="text" name="jenis_tunjangan"
                        class="form-control"
                        value="{{ $tunjangan->jenis_tunjangan }}" required>
                </div>

                <div class="mb-3">
                    <label>Nama Tunjangan</label>
                    <input type="text" name="nama_tunjangan"
                        class="form-control"
                        value="{{ $tunjangan->nama_tunjangan }}" required>
                </div>

                <div class="mb-3">
                    <label>Instansi</label>
                    <input type="text" name="instansi"
                        class="form-control"
                        value="{{ $tunjangan->instansi }}">
                </div>

                <div class="mb-3">
                    <label>Nomor SK Tunjangan</label>
                    <input type="text" name="sk_tunjangan"
                        class="form-control"
                        value="{{ $tunjangan->sk_tunjangan }}">
                </div>

                <div class="mb-3">
                    <label>Tanggal SK Tunjangan</label>
                    <input type="date" name="tgl_sk_tunjangan"
                        class="form-control"
                        value="{{ $tunjangan->tgl_sk_tunjangan }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Semester</label>
                    <select name="semester_id" class="form-control" required>
                        <option value="">-- Pilih Semester --</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}"
                                {{ $tunjangan->semester_id == $semester->id ? 'selected' : '' }}>
                                {{ $semester->nama_semester }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Sumber Dana</label>
                    <input type="text" name="sumber_dana"
                        class="form-control"
                        value="{{ $tunjangan->sumber_dana }}">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Dari Tahun</label>
                        <input type="number" name="dari_tahun"
                            class="form-control"
                            value="{{ $tunjangan->dari_tahun }}" min="1900" max="2100">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Sampai Tahun</label>
                        <input type="number" name="sampai_tahun"
                            class="form-control"
                            value="{{ $tunjangan->sampai_tahun }}" min="1900" max="2100">
                    </div>
                </div>

                <div class="mb-3">
                    <label>Nominal (Rp)</label>
                    <input type="number" name="nominal"
                        class="form-control"
                        value="{{ $tunjangan->nominal }}" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="Masih Menerima" {{ $tunjangan->status == 'Masih Menerima' ? 'selected' : '' }}>Masih Menerima</option>
                        <option value="Tidak Menerima" {{ $tunjangan->status == 'Tidak Menerima' ? 'selected' : '' }}>Tidak Menerima</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix . '.tunjangan.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">{{ $tunjangan->id ? 'Perbarui' : 'Simpan' }}</button>
        </div>
    </form>
</div>

@endsection
