@extends('layouts.app')

@section('content')

@php
    $user = auth()->user();
    $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';
@endphp

<div class="container">
    <h3>{{ isset($riwayatKepangkatan) ? 'Edit' : 'Tambah' }} Riwayat Kepangkatan</h3>

    <form action="{{ isset($riwayatKepangkatan)
        ? route($prefix.'riwayat-kepangkatan.update', $riwayatKepangkatan->id)
        : route($prefix.'riwayat-kepangkatan.store') }}"
        method="POST">

        @csrf
        @if(isset($riwayatKepangkatan))
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
                                <option value="{{ $p->id }}"
                                    {{ isset($riwayatKepangkatan) && $riwayatKepangkatan->ptk_id == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                    @endif

                </div>

                <div class="mb-3">
                    <label>Pangkat Golongan</label>
                    <input type="text" name="pangkat_golongan" class="form-control"
                           value="{{ $riwayatKepangkatan->pangkat_golongan ?? '' }}" required>
                </div>

                <div class="mb-3">
                    <label>Nomor SK</label>
                    <input type="text" name="nomor_sk" class="form-control"
                           value="{{ $riwayatKepangkatan->nomor_sk ?? '' }}" required>
                </div>

                <div class="mb-3">
                    <label>Tanggal SK</label>
                    <input type="date" name="tanggal_sk" class="form-control"
                           value="{{ $riwayatKepangkatan->tanggal_sk ?? '' }}" required>
                </div>

            </div>

            <div class="col-md-6">

                <div class="mb-3">
                    <label>TMT Pangkat</label>
                    <input type="date" name="tmt_pangkat" class="form-control"
                           value="{{ $riwayatKepangkatan->tmt_pangkat ?? '' }}" required>
                </div>

                <div class="mb-3">
                    <label>Masa Kerja (Tahun)</label>
                    <input type="number" name="masa_kerja_thn" class="form-control"
                           value="{{ $riwayatKepangkatan->masa_kerja_thn ?? '' }}" min="0" required>
                </div>

                <div class="mb-3">
                    <label>Masa Kerja (Bulan)</label>
                    <input type="number" name="masa_kerja_bln" class="form-control"
                           value="{{ $riwayatKepangkatan->masa_kerja_bln ?? '' }}" min="0" max="11" required>
                </div>

            </div>

        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix.'riwayat-kepangkatan.index') }}"
               class="btn btn-secondary me-2">Kembali</a>

            <button type="submit" class="btn btn-success">
                {{ isset($riwayatKepangkatan) ? 'Update' : 'Simpan' }}
            </button>
        </div>

    </form>

</div>

@endsection
