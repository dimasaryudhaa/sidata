@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ $data->id ? 'Edit Penugasan PTK' : 'Tambah Penugasan PTK' }}</h1>

    @php
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $prefix = $isPtk ? 'ptk.' : 'admin.';
    @endphp

    <form action="{{ $data->id
            ? route($prefix.'penugasan-ptk.update', $data->id)
            : route($prefix.'penugasan-ptk.store')
        }}" method="POST">

        @csrf
        @if($data->id)
            @method('PUT')
        @endif

        <div class="row">

            <div class="col-md-6">

                <div class="mb-3">
                    <label>Nama PTK</label>

                    @if(!$isPtk)
                        <select name="ptk_id" class="form-control" required>
                            <option value="">Pilih PTK</option>
                            @foreach($ptks as $p)
                                <option value="{{ $p->id }}" {{ $data->ptk_id == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>

                    @else
                        <input type="text" class="form-control" value="{{ $data->ptk->nama_lengkap ?? $user->name }}" readonly>
                        <input type="hidden" name="ptk_id" value="{{ $data->ptk_id }}">
                    @endif
                </div>

                <div class="mb-3">
                    <label>Nomor Surat Tugas</label>
                    <input type="text" name="nomor_surat_tugas" class="form-control"
                           value="{{ $data->nomor_surat_tugas ?? '' }}" required>
                </div>

                <div class="mb-3">
                    <label>Tanggal Surat Tugas</label>
                    <input type="date" name="tanggal_surat_tugas" class="form-control"
                           value="{{ $data->tanggal_surat_tugas ?? '' }}" required>
                </div>
            </div>

            <div class="col-md-6">

                <div class="mb-3">
                    <label>TMT Tugas</label>
                    <input type="date" name="tmt_tugas" class="form-control"
                           value="{{ $data->tmt_tugas ?? '' }}" required>
                </div>

                <div class="mb-3">
                    <label>Status Sekolah Induk</label>
                    <select name="status_sekolah_induk" class="form-control" required>
                        <option value="">Pilih Status</option>
                        <option value="1" {{ $data->status_sekolah_induk == 1 ? 'selected' : '' }}>Ya</option>
                        <option value="0" {{ $data->status_sekolah_induk == 0 ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix.'penugasan-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">
                {{ $data->id ? 'Perbarui' : 'Simpan' }}
            </button>
        </div>

    </form>
</div>
@endsection
