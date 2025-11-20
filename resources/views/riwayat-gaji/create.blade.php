@extends('layouts.app')

@section('content')

@php
    $user = auth()->user();
    $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';
@endphp

<div class="container">
    <h3>Tambah Riwayat Gaji</h3>

    <form action="{{ route($prefix.'riwayat-gaji.store') }}" method="POST">
        @csrf
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
                                <option value="{{ $p->id }}">{{ $p->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="mb-3">
                    <label>Pangkat Golongan</label>
                    <input type="text" name="pangkat_golongan" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Nomor SK</label>
                    <input type="text" name="nomor_sk" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Tanggal SK</label>
                    <input type="date" name="tanggal_sk" class="form-control" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>TMT KGB</label>
                    <input type="date" name="tmt_kgb" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Masa Kerja (Tahun)</label>
                    <input type="number" name="masa_kerja_thn" class="form-control" min="0" required>
                </div>

                <div class="mb-3">
                    <label>Masa Kerja (Bulan)</label>
                    <input type="number" name="masa_kerja_bln" class="form-control" min="0" max="11" required>
                </div>

                <div class="mb-3">
                    <label>Gaji Pokok</label>
                    <input type="number" name="gaji_pokok" class="form-control" min="0" step="0.01" required>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix.'riwayat-gaji.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>

@endsection
