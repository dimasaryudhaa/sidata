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
        <table class="table table-bordered" id="anakPtkTable">
            <thead class="text-white">
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama Anak</th>
                    <th>Status Anak</th>
                    <th>Jenjang</th>
                    <th>NISN</th>
                    <th>Jenis Kelamin</th>
                    <th>Tempat, Tanggal Lahir</th>
                    <th>Tahun Masuk</th>
                    <th style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($anak as $index => $a)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $a->nama_anak ?? '-' }}</td>
                    <td>{{ $a->status_anak ?? '-' }}</td>
                    <td>{{ $a->jenjang ?? '-' }}</td>
                    <td>{{ $a->nisn ?? '-' }}</td>
                    <td>{{ $a->jenis_kelamin ?? '-' }}</td>
                    <td>{{ $a->tempat_lahir ?? '-' }}, {{ $a->tanggal_lahir ? date('d-m-Y', strtotime($a->tanggal_lahir)) : '-' }}</td>
                    <td>{{ $a->tahun_masuk ?? '-' }}</td>
                    <td>
                        <a href="{{ route('anak-ptk.edit', $a->ptk_id) }}" class="btn btn-sm btn-no-border">
                            <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px; height:20px;">
                        </a>
                        <form action="{{ route('anak-ptk.destroy', $a->ptk_id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-no-border"
                                onclick="return confirm('Yakin ingin menghapus semua data anak PTK ini?')">
                                <img src="{{ asset('images/delete.png') }}" alt="Hapus" style="width:20px; height:20px;">
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">Belum ada data anak untuk PTK ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <a href="{{ route('anak-ptk.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
</div>

@endsection
