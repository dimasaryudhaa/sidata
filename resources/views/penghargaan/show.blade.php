@extends('layouts.app')

@section('content')

@php
    $user = auth()->user();
    $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';
@endphp

<style>
.table thead th {
    background: linear-gradient(180deg, #0770d3, #007efd, #55a6f8) !important;
    color: white !important;
    border: none !important;
    vertical-align: middle !important;
    font-weight: 600;
}

.btn-no-border {
    border: none !important;
    box-shadow: none !important;
    background: transparent !important;
    padding: 0;
}

.btn-no-border:focus,
.btn-no-border:active,
.btn-no-border:hover {
    border: none !important;
    box-shadow: none !important;
    background: transparent !important;
}
</style>

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>{{ $ptk->nama_lengkap }}</h4>
    </div>

    <div class="table-responsive rounded-3 overflow-hidden mt-3">
        <table class="table table-bordered" id="penghargaanPtkTable">
            <thead class="text-white">
                <tr>
                    <th style="width:60px;">No</th>
                    <th>Tingkat Penghargaan</th>
                    <th>Jenis Penghargaan</th>
                    <th>Nama Penghargaan</th>
                    <th>Tahun</th>
                    <th>Instansi</th>
                    <th style="width:120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penghargaan as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->tingkat_penghargaan ?? '-' }}</td>
                    <td>{{ $p->jenis_penghargaan ?? '-' }}</td>
                    <td>{{ $p->nama_penghargaan ?? '-' }}</td>
                    <td>{{ $p->tahun ?? '-' }}</td>
                    <td>{{ $p->instansi ?? '-' }}</td>
                    <td>
                        <a href="{{ route($prefix.'penghargaan.edit', $p->id) }}" class="btn btn-sm btn-no-border">
                            <img src="{{ asset('images/edit.png') }}" alt="Edit Penghargaan" style="width:20px; height:20px;">
                        </a>
                        <form action="{{ route($prefix.'penghargaan.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data penghargaan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-no-border">
                                <img src="{{ asset('images/delete.png') }}" alt="Hapus Penghargaan" style="width:20px; height:20px;">
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data penghargaan untuk PTK ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route($prefix.'penghargaan.index') }}" class="btn btn-sm btn-secondary mt-3">Kembali</a>
</div>

@endsection
