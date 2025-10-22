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
        <h4>{{ $ptk->nama_lengkap }}</h4>
    </div>

    <div class="table-responsive rounded-3 overflow-hidden mt-3">
        <table class="table table-bordered" id="diklatPtkTable">
            <thead class="text-white">
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Jenis Diklat</th>
                    <th>Nama Diklat</th>
                    <th>No Sertifikat</th>
                    <th>Penyelenggara</th>
                    <th>Tahun</th>
                    <th>Peran</th>
                    <th>Tingkat</th>
                    <th style="width:80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($diklat as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->jenis_diklat ?? '-' }}</td>
                    <td>{{ $item->nama_diklat ?? '-' }}</td>
                    <td>{{ $item->no_sertifikat ?? '-' }}</td>
                    <td>{{ $item->penyelenggara ?? '-' }}</td>
                    <td>{{ $item->tahun ?? '-' }}</td>
                    <td>{{ $item->peran ?? '-' }}</td>
                    <td>{{ $item->tingkat ?? '-' }}</td>
                    <td>
                        <a href="{{ route('diklat.edit', $item->id) }}" class="btn btn-sm btn-no-border">
                            <img src="{{ asset('images/edit.png') }}" alt="Edit Diklat" style="width:20px; height:20px;">
                        </a>
                        <form action="{{ route('diklat.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-no-border">
                                <img src="{{ asset('images/delete.png') }}" alt="Hapus Diklat" style="width:20px; height:20px;">
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center">Belum ada data diklat untuk PTK ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <a href="{{ route('diklat.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
</div>

@endsection
