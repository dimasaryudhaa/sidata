@extends('layouts.app')

@section('content')

<div class="container">
    <h1 class="mb-4">{{ $kesejahteraanPtk->id ? 'Edit Kesejahteraan PTK' : 'Tambah Kesejahteraan PTK' }}</h1>

    @php
        $user = auth()->user();
        $prefix = $user->role === 'admin' ? 'admin' : 'ptk';
    @endphp

    <form action="{{ $kesejahteraanPtk->id
            ? route($prefix . '.kesejahteraan-ptk.update', $kesejahteraanPtk->id)
            : route($prefix . '.kesejahteraan-ptk.store')
        }}" method="POST">
        @csrf
        @if($kesejahteraanPtk->id)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <input type="text" class="form-control"
                        value="{{ $ptk->nama_lengkap ?? $kesejahteraanPtk->ptk->nama_lengkap }}" readonly>
                    <input type="hidden" name="ptk_id"
                        value="{{ $ptk->id ?? $kesejahteraanPtk->ptk_id }}">
                </div>

                <div class="mb-3">
                    <label>Jenis Kesejahteraan</label>
                    <input type="text" name="jenis_kesejahteraan" class="form-control"
                        value="{{ $kesejahteraanPtk->jenis_kesejahteraan }}" required>
                </div>

                <div class="mb-3">
                    <label>Nama Kesejahteraan</label>
                    <input type="text" name="nama" class="form-control"
                        value="{{ $kesejahteraanPtk->nama }}" required>
                </div>

                <div class="mb-3">
                    <label>Penyelenggara</label>
                    <input type="text" name="penyelenggara" class="form-control"
                        value="{{ $kesejahteraanPtk->penyelenggara }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Dari Tahun</label>
                    <input type="number" name="dari_tahun" class="form-control"
                        value="{{ $kesejahteraanPtk->dari_tahun }}" min="1900" max="2100">
                </div>

                <div class="mb-3">
                    <label>Sampai Tahun</label>
                    <input type="number" name="sampai_tahun" class="form-control"
                        value="{{ $kesejahteraanPtk->sampai_tahun }}" min="1900" max="2100">
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="Masih Menerima" {{ $kesejahteraanPtk->status == 'Masih Menerima' ? 'selected' : '' }}>Masih Menerima</option>
                        <option value="Tidak Menerima" {{ $kesejahteraanPtk->status == 'Tidak Menerima' ? 'selected' : '' }}>Tidak Menerima</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix . '.kesejahteraan-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">{{ $kesejahteraanPtk->id ? 'Perbarui' : 'Simpan' }}</button>
        </div>
    </form>
</div>

@endsection
