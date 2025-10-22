@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ $data->id ? 'Edit Keluarga PTK' : 'Tambah Keluarga PTK' }}</h1>

    <form action="{{ $data->id ? route('keluarga-ptk.update', $data->id) : route('keluarga-ptk.store') }}" method="POST">
        @csrf
        @if($data->id)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <select name="ptk_id" class="form-control" required>
                        <option value="">Pilih PTK</option>
                        @foreach($ptks as $p)
                            <option value="{{ $p->id }}" {{ $data->ptk_id == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>No KK</label>
                    <input type="text" name="no_kk" class="form-control" maxlength="16" value="{{ $data->no_kk }}">
                </div>

                <div class="mb-3">
                    <label>Status Perkawinan</label>
                    <select name="status_perkawinan" class="form-control">
                        <option value="">Pilih Status</option>
                        <option value="Kawin" {{ $data->status_perkawinan == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                        <option value="Belum Kawin" {{ $data->status_perkawinan == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                        <option value="Janda/Duda" {{ $data->status_perkawinan == 'Janda/Duda' ? 'selected' : '' }}>Janda/Duda</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama Suami/Istri</label>
                    <input type="text" name="nama_suami_istri" class="form-control" value="{{ $data->nama_suami_istri }}">
                </div>

                <div class="mb-3">
                    <label>NIP Suami/Istri</label>
                    <input type="text" name="nip_suami_istri" class="form-control" maxlength="25" value="{{ $data->nip_suami_istri }}">
                </div>

                <div class="mb-3">
                    <label>Pekerjaan Suami/Istri</label>
                    <select name="pekerjaan_suami_istri" class="form-control">
                        <option value="">Pilih Pekerjaan</option>
                        @foreach(['Tidak Bekerja','Nelayan','Petani','Peternak','PNS','Swasta','Wiraswasta','Pedagang','Buruh','Pensiunan','Sudah Meninggal'] as $pekerjaan)
                            <option value="{{ $pekerjaan }}" {{ $data->pekerjaan_suami_istri == $pekerjaan ? 'selected' : '' }}>
                                {{ $pekerjaan }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('keluarga-ptk.index') }}" class="btn btn-secondary me-2">Batal</a>
            <button type="submit" class="btn btn-success">
                {{ $data->id ? 'Perbarui' : 'Simpan' }}
            </button>
        </div>
    </form>
</div>
@endsection
