@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';
@endphp

<div class="container">
    <h3>{{ isset($kesejahteraanPtk) ? 'Edit Kesejahteraan PTK' : 'Tambah Kesejahteraan PTK' }}</h3>

    <form action="{{ isset($kesejahteraanPtk)
            ? route($prefix.'kesejahteraan-ptk.update', $kesejahteraanPtk->id)
            : route($prefix.'kesejahteraan-ptk.store') }}" method="POST">
        @csrf
        @if(isset($kesejahteraanPtk))
            @method('PUT')
        @endif

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
                            @foreach($ptks as $p)
                                <option value="{{ $p->id }}" {{ (old('ptk_id', $kesejahteraanPtk->ptk_id ?? '') == $p->id) ? 'selected' : '' }}>
                                    {{ $p->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="mb-3">
                    <label>Jenis Kesejahteraan</label>
                    <input type="text" name="jenis_kesejahteraan" class="form-control"
                           value="{{ old('jenis_kesejahteraan', $kesejahteraanPtk->jenis_kesejahteraan ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label>Nama Kesejahteraan</label>
                    <input type="text" name="nama" class="form-control"
                           value="{{ old('nama', $kesejahteraanPtk->nama ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label>Penyelenggara</label>
                    <input type="text" name="penyelenggara" class="form-control"
                           value="{{ old('penyelenggara', $kesejahteraanPtk->penyelenggara ?? '') }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Dari Tahun</label>
                    <input type="number" name="dari_tahun" class="form-control" min="1900" max="2100"
                           value="{{ old('dari_tahun', $kesejahteraanPtk->dari_tahun ?? '') }}">
                </div>

                <div class="mb-3">
                    <label>Sampai Tahun</label>
                    <input type="number" name="sampai_tahun" class="form-control" min="1900" max="2100"
                           value="{{ old('sampai_tahun', $kesejahteraanPtk->sampai_tahun ?? '') }}">
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="Masih Menerima" {{ old('status', $kesejahteraanPtk->status ?? '') == 'Masih Menerima' ? 'selected' : '' }}>Masih Menerima</option>
                        <option value="Tidak Menerima" {{ old('status', $kesejahteraanPtk->status ?? '') == 'Tidak Menerima' ? 'selected' : '' }}>Tidak Menerima</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix.'kesejahteraan-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">{{ isset($kesejahteraanPtk) ? 'Update' : 'Simpan' }}</button>
        </div>
    </form>
</div>

@endsection
