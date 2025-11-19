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

.table td {
    vertical-align: middle;
}
</style>

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>{{ $ptk->nama_lengkap }}</h4>
    </div>

    <div class="table-responsive rounded-3 overflow-hidden">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Jenis Kesejahteraan</th>
                    <th>Nama</th>
                    <th>Penyelenggara</th>
                    <th>Dari Tahun</th>
                    <th>Sampai Tahun</th>
                    <th>Status</th>
                    <th style="width:80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kesejahteraan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->jenis_kesejahteraan ?? '-' }}</td>
                    <td>{{ $item->nama ?? '-' }}</td>
                    <td>{{ $item->penyelenggara ?? '-' }}</td>
                    <td>{{ $item->dari_tahun ?? '-' }}</td>
                    <td>{{ $item->sampai_tahun ?? '-' }}</td>
                    <td>{{ $item->status ?? '-' }}</td>
                    <td>
                        <a href="{{ route($prefix.'kesejahteraan-ptk.edit', ['kesejahteraan_ptk' => $item->id]) }}" class="btn btn-sm btn-no-border">
                            <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px; height:20px;">
                        </a>

                        <form action="{{ route($prefix.'kesejahteraan-ptk.destroy', ['kesejahteraan_ptk' => $item->id]) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
                    <td colspan="8" class="text-center text-muted">Belum ada data kesejahteraan untuk PTK ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route($prefix.'kesejahteraan-ptk.index') }}" class="btn btn-secondary mt-3">Kembali</a>

</div>

@endsection
