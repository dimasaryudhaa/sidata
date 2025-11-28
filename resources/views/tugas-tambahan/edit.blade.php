@extends('layouts.app')

@section('content')

<div class="container">

    <h1 class="mb-4">{{ $tugasTambahan->id ? 'Edit Tugas Tambahan' : 'Tambah Tugas Tambahan' }}</h1>

    @php
        use Illuminate\Support\Facades\Auth;

        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin' : 'ptk';
    @endphp

    <form action="{{ $tugasTambahan->id
            ? route($prefix . '.tugas-tambahan.update', $tugasTambahan->id)
            : route($prefix . '.tugas-tambahan.store')
        }}" method="POST">

        @csrf
        @if($tugasTambahan->id)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <input type="text" class="form-control"
                           value="{{ $ptk->nama_lengkap ?? $tugasTambahan->ptk->nama_lengkap }}"
                           readonly>
                    <input type="hidden" name="ptk_id"
                           value="{{ $ptk->id ?? $tugasTambahan->ptk_id }}">
                </div>

                <div class="mb-3">
                    <label>Jabatan PTK</label>
                    <input type="text" name="jabatan_ptk" class="form-control"
                           value="{{ $tugasTambahan->jabatan_ptk }}" required>
                </div>

                <div class="mb-3">
                    <label>Prasarana</label>
                    <input type="text" name="prasarana" class="form-control"
                           value="{{ $tugasTambahan->prasarana }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nomor SK</label>
                    <input type="text" name="nomor_sk" class="form-control"
                           value="{{ $tugasTambahan->nomor_sk }}" required>
                </div>

                <div class="mb-3">
                    <label>TMT Tambahan</label>
                    <input type="date" name="tmt_tambahan" class="form-control"
                           value="{{ $tugasTambahan->tmt_tambahan }}" required>
                </div>

                <div class="mb-3">
                    <label>TST Tambahan</label>
                    <input type="date" name="tst_tambahan" class="form-control"
                           value="{{ $tugasTambahan->tst_tambahan }}">
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix . '.tugas-tambahan.index') }}"
               class="btn btn-secondary me-2">Kembali</a>

            <button type="submit" class="btn btn-success">
                {{ $tugasTambahan->id ? 'Perbarui' : 'Simpan' }}
            </button>
        </div>

    </form>

</div>

@endsection
