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
</style>

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>{{ $ptk->nama_lengkap }}</h4>
    </div>

    <div class="table-responsive rounded-3 overflow-hidden mt-3">
        <table class="table table-bordered" id="riwayatGajiTable">
            <thead class="text-white">
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Pangkat Golongan</th>
                    <th>Nomor SK</th>
                    <th>Tanggal SK</th>
                    <th>TMT KGB</th>
                    <th>Masa Kerja</th>
                    <th>Gaji Pokok</th>
                    <th style="width:80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($riwayatGaji as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->pangkat_golongan }}</td>
                    <td>{{ $item->nomor_sk }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_sk)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tmt_kgb)->format('d-m-Y') }}</td>
                    <td>{{ $item->masa_kerja_thn }} Thn {{ $item->masa_kerja_bln }} Bln</td>
                    <td>{{ number_format($item->gaji_pokok, 2, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('riwayat-gaji.edit', $item->id) }}" class="btn btn-sm btn-no-border">
                            <img src="{{ asset('images/edit.png') }}" alt="Edit Riwayat Gaji" style="width:20px; height:20px;">
                        </a>
                        <form action="{{ route('riwayat-gaji.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-no-border">
                                <img src="{{ asset('images/delete.png') }}" alt="Hapus Riwayat Gaji" style="width:20px; height:20px;">
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <a href="{{ route('riwayat-gaji.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>

@endsection
