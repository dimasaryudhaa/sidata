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
        <h4>{{ $siswa->nama_lengkap }}</h4>
    </div>

    <div class="table-responsive rounded-3 overflow-hidden mt-3">
        <table class="table table-bordered" id="kesejahteraanTable">
            <thead class="text-white">
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Jenis Kesejahteraan</th>
                    <th>No Kartu</th>
                    <th>Nama di Kartu</th>
                    <th style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kesejahteraan as $index => $k)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $k->jenis_kesejahteraan ?? '-' }}</td>
                    <td>{{ $k->no_kartu ?? '-' }}</td>
                    <td>{{ $k->nama_di_kartu ?? '-' }}</td>
                    <td>
                        <a href="{{ route('kesejahteraan-siswa.edit', $k->peserta_didik_id) }}" class="btn btn-sm btn-no-border">
                            <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px; height:20px;">
                        </a>
                        <form action="{{ route('kesejahteraan-siswa.destroy', $k->peserta_didik_id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-no-border"
                                onclick="return confirm('Yakin ingin menghapus semua data kesejahteraan siswa ini?')">
                                <img src="{{ asset('images/delete.png') }}" alt="Hapus" style="width:20px; height:20px;">
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data kesejahteraan siswa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <a href="{{ route('kesejahteraan-siswa.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
</div>

@endsection
