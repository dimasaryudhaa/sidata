@extends('layouts.app')

@section('content')

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
        <h3>{{ $ptk->nama_lengkap }}</h3>
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
                    <th>Masa Kerja (Thn/Bln)</th>
                    <th style="width:80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($riwayatKepangkatan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->pangkat_golongan }}</td>
                    <td>{{ $item->nomor_sk }}</td>
                    <td>{{ $item->tanggal_sk }}</td>
                    <td>{{ $item->tmt_pangkat }}</td>
                    <td>{{ $item->masa_kerja_thn }} Thn / {{ $item->masa_kerja_bln }} Bln</td>
                    <td>
                        <a href="{{ route('riwayat-kepangkatan.edit', $item->id) }}" class="btn btn-sm btn-no-border">
                            <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px; height:20px;">
                        </a>
                        <form action="{{ route('riwayat-kepangkatan.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-no-border" onclick="return confirm('Yakin ingin menghapus?')">
                                <img src="{{ asset('images/delete.png') }}" alt="Hapus" style="width:20px; height:20px;">
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @if(count($riwayatKepangkatan) == 0)
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <a href="{{ route('riwayat-kepangkatan.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>

@endsection
