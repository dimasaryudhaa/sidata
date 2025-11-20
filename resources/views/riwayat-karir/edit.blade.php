@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Riwayat Karir PTK</h1>

    @php
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin' : 'ptk';
    @endphp

    <form action="{{ route($prefix . '.riwayat-karir.update', $riwayatKarir->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-md-6">

                <div class="mb-3">
                    <label>Nama PTK</label>
                    <input type="text" class="form-control"
                        value="{{ $ptk->nama_lengkap ?? $riwayatKarir->ptk->nama_lengkap }}"
                        readonly>

                    <input type="hidden" name="ptk_id"
                        value="{{ $ptk->id ?? $riwayatKarir->ptk_id }}">
                </div>

                <div class="mb-3">
                    <label>Jenjang Pendidikan</label>
                    <input type="text" name="jenjang_pendidikan" class="form-control"
                           value="{{ $riwayatKarir->jenjang_pendidikan }}" required>
                </div>

                <div class="mb-3">
                    <label>Jenis Lembaga</label>
                    <input type="text" name="jenis_lembaga" class="form-control"
                           value="{{ $riwayatKarir->jenis_lembaga }}" required>
                </div>

                <div class="mb-3">
                    <label>Status Kepegawaian</label>
                    <input type="text" name="status_kepegawaian" class="form-control"
                           value="{{ $riwayatKarir->status_kepegawaian }}" required>
                </div>

                <div class="mb-3">
                    <label>Jenis PTK</label>
                    <input type="text" name="jenis_ptk" class="form-control"
                           value="{{ $riwayatKarir->jenis_ptk }}" required>
                </div>

                <div class="mb-3">
                    <label>Lembaga Pengangkat</label>
                    <input type="text" name="lembaga_pengangkat" class="form-control"
                           value="{{ $riwayatKarir->lembaga_pengangkat }}" required>
                </div>

            </div>

            <div class="col-md-6">

                <div class="mb-3">
                    <label>No SK Kerja</label>
                    <input type="text" name="no_sk_kerja" class="form-control"
                           value="{{ $riwayatKarir->no_sk_kerja }}" required>
                </div>

                <div class="mb-3">
                    <label>Tanggal SK Kerja</label>
                    <input type="date" name="tgl_sk_kerja" class="form-control"
                           value="{{ $riwayatKarir->tgl_sk_kerja }}" required>
                </div>

                <div class="mb-3">
                    <label>TMT Kerja</label>
                    <input type="date" name="tmt_kerja" class="form-control"
                           value="{{ $riwayatKarir->tmt_kerja }}" required>
                </div>

                <div class="mb-3">
                    <label>TST Kerja</label>
                    <input type="date" name="tst_kerja" class="form-control"
                           value="{{ $riwayatKarir->tst_kerja }}">
                </div>

                <div class="mb-3">
                    <label>Tempat Kerja</label>
                    <input type="text" name="tempat_kerja" class="form-control"
                           value="{{ $riwayatKarir->tempat_kerja }}" required>
                </div>

                <div class="mb-3">
                    <label>TTD SK Kerja</label>
                    <input type="text" name="ttd_sk_kerja" class="form-control"
                           value="{{ $riwayatKarir->ttd_sk_kerja }}" required>
                </div>

            </div>

        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix . '.riwayat-karir.index') }}"
               class="btn btn-secondary me-2">Kembali</a>

            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>

    </form>
</div>
@endsection
