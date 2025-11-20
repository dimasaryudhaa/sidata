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
        <table class="table table-bordered" id="riwayatKepangkatanTable">
            <thead class="text-white">
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Pangkat Golongan</th>
                    <th>Nomor SK</th>
                    <th>Tanggal SK</th>
                    <th>TMT Pangkat</th>
                    <th>Masa Kerja</th>
                    <th style="width:80px;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($riwayatKepangkatan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->pangkat_golongan ?? '-' }}</td>
                    <td>{{ $item->nomor_sk ?? '-' }}</td>

                    <td>
                        {{ $item->tanggal_sk
                            ? \Carbon\Carbon::parse($item->tanggal_sk)->format('d-m-Y')
                            : '-' }}
                    </td>

                    <td>
                        {{ $item->tmt_pangkat
                            ? \Carbon\Carbon::parse($item->tmt_pangkat)->format('d-m-Y')
                            : '-' }}
                    </td>

                    <td>
                        {{ $item->masa_kerja_thn }} Thn /
                        {{ $item->masa_kerja_bln }} Bln
                    </td>

                    <td>
                        <a href="{{ route($prefix.'riwayat-kepangkatan.edit', $item->id) }}"
                           class="btn btn-sm btn-no-border">
                            <img src="{{ asset('images/edit.png') }}"
                                 alt="Edit Riwayat"
                                 style="width:20px; height:20px;">
                        </a>

                        <form action="{{ route($prefix.'riwayat-kepangkatan.destroy', $item->id) }}"
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
                    <td colspan="7" class="text-center">Belum ada data riwayat kepangkatan untuk PTK ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route($prefix.'riwayat-kepangkatan.index') }}"
       class="btn btn-sm btn-secondary mt-3">Kembali</a>

</div>

@endsection
