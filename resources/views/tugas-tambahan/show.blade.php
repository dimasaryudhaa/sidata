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

    .btn-no-border:hover,
    .btn-no-border:focus,
    .btn-no-border:active {
        background: transparent !important;
        box-shadow: none !important;
        border: none !important;
    }
</style>

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>{{ $ptk->nama_lengkap }}</h4>
    </div>

    <div class="table-responsive rounded-3 overflow-hidden mt-3">
        <table class="table table-bordered" id="tugasTambahanTable">
            <thead class="text-white">
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Jabatan PTK</th>
                    <th>Prasarana</th>
                    <th>Nomor SK</th>
                    <th>TMT Tambahan</th>
                    <th>TST Tambahan</th>
                    <th style="width:80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tugasTambahan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->jabatan_ptk }}</td>
                    <td>{{ $item->prasarana ?? '-' }}</td>
                    <td>{{ $item->nomor_sk }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tmt_tambahan)->format('d-m-Y') }}</td>
                    <td>
                        @if($item->tst_tambahan)
                            {{ \Carbon\Carbon::parse($item->tst_tambahan)->format('d-m-Y') }}
                        @else
                            <span class="text-muted">Masih Menjabat</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route($prefix.'tugas-tambahan.edit', $item->id) }}"
                           class="btn btn-sm btn-no-border">
                            <img src="{{ asset('images/edit.png') }}"
                                 alt="Edit"
                                 style="width:20px; height:20px;">
                        </a>
                        <form action="{{ route($prefix.'tugas-tambahan.destroy', $item->id) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-no-border">
                                <img src="{{ asset('images/delete.png') }}"
                                     alt="Hapus"
                                     style="width:20px; height:20px;">
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data tugas tambahan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route($prefix.'tugas-tambahan.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>

@endsection
