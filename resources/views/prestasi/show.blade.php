@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';
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
        <h4>{{ $siswa->nama_lengkap }}</h4>
    </div>

    <div class="table-responsive rounded-3 overflow-hidden mt-3">
        <table class="table table-bordered" id="prestasiTable">
            <thead class="text-white">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Jenis Prestasi</th>
                    <th>Tingkat</th>
                    <th>Nama Prestasi</th>
                    <th>Tahun</th>
                    <th>Penyelenggara</th>
                    <th>Peringkat</th>
                    <th style="width: 80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prestasi as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->jenis_prestasi }}</td>
                    <td>{{ $p->tingkat_prestasi }}</td>
                    <td>{{ $p->nama_prestasi }}</td>
                    <td>{{ $p->tahun_prestasi }}</td>
                    <td>{{ $p->penyelenggara }}</td>
                    <td>{{ $p->peringkat ?? '-' }}</td>
                    <td>
                        <a href="{{ route($prefix.'prestasi.edit', $p->id) }}" class="btn btn-sm btn-no-border">
                            <img src="{{ asset('images/edit.png') }}" alt="Edit Prestasi" style="width:20px; height:20px;">
                        </a>

                        <form action="{{ route($prefix.'prestasi.destroy', $p->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus prestasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-no-border">
                                <img src="{{ asset('images/delete.png') }}" alt="Hapus Prestasi" style="width:20px; height:20px;">
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada data prestasi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route($prefix.'prestasi.index') }}" class="btn btn-sm btn-secondary mt-3">Kembali</a>
</div>

@endsection
