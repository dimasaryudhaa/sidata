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
        <table class="table table-bordered" id="tunjanganTable">
            <thead class="text-white">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Jenis Tunjangan</th>
                    <th>Nama Tunjangan</th>
                    <th>Semester</th>
                    <th>Nominal (Rp)</th>
                    <th>Status</th>
                    <th style="width: 80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tunjangan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->jenis_tunjangan ?? '-' }}</td>
                    <td>{{ $item->nama_tunjangan ?? '-' }}</td>
                    <td>{{ $item->semester->nama_semester ?? '-' }} - {{ $item->semester->tahun_ajaran ?? '-' }}</td>
                    <td>{{ $item->nominal ? number_format($item->nominal, 0, ',', '.') : '-' }}</td>
                    <td>{{ $item->status ?? '-' }}</td>
                    <td>
                        <a href="{{ route($prefix.'tunjangan.edit', $item->id) }}" class="btn btn-sm btn-no-border">
                            <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px; height:20px;">
                        </a>

                        <form action="{{ route($prefix.'tunjangan.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data tunjangan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-no-border">
                                <img src="{{ asset('images/delete.png') }}" alt="Hapus" style="width:20px; height:20px;">
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Belum ada data tunjangan untuk PTK ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route($prefix.'tunjangan.index') }}" class="btn btn-sm btn-secondary mt-3">Kembali</a>

</div>

@endsection
