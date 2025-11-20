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
        <table class="table table-bordered" id="kompetensiPtkTable">
            <thead class="text-white">
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Bidang Studi</th>
                    <th>Urutan</th>
                    <th style="width:120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kompetensi as $index => $k)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $k->bidang_studi ?? '-' }}</td>
                    <td>{{ $k->urutan ?? '-' }}</td>
                    <td>
                        <a href="{{ route($prefix.'kompetensi-ptk.edit', $k->id) }}" class="btn btn-sm btn-no-border">
                            <img src="{{ asset('images/edit.png') }}" alt="Edit Kompetensi" style="width:20px; height:20px;">
                        </a>
                        <form action="{{ route($prefix.'kompetensi-ptk.destroy', $k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data kompetensi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-no-border">
                                <img src="{{ asset('images/delete.png') }}" alt="Hapus Kompetensi" style="width:20px; height:20px;">
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada data kompetensi untuk PTK ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route($prefix.'kompetensi-ptk.index') }}" class="btn btn-sm btn-secondary mt-3">Kembali</a>
</div>

@endsection
