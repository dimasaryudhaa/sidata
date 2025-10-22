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
        <table class="table table-bordered" id="pendidikanPtkTable">
            <thead class="text-white">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Jenjang Pendidikan</th>
                    <th>Bidang Studi</th>
                    <th>Gelar Akademik</th>
                    <th>Tahun Masuk</th>
                    <th>Tahun Lulus</th>
                    <th>Nama PTK</th>
                    <th style="width: 80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendidikan as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->jenjang_pendidikan ?? '-' }}</td>
                    <td>{{ $p->bidang_studi ?? '-' }}</td>
                    <td>{{ $p->gelar_akademik ?? '-' }}</td>
                    <td>{{ $p->tahun_masuk ?? '-' }}</td>
                    <td>{{ $p->tahun_lulus ?? '-' }}</td>
                    <td>{{ $ptk->nama_lengkap ?? '-' }}</td>
                    <td>
                        <a href="{{ route('pendidikan-ptk.edit', $p->id) }}" class="btn btn-sm btn-no-border">
                            <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px; height:20px;">
                        </a>
                        <form action="{{ route('pendidikan-ptk.destroy', $p->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-no-border"
                                onclick="return confirm('Yakin ingin menghapus data pendidikan ini?')">
                                <img src="{{ asset('images/delete.png') }}" alt="Hapus" style="width:20px; height:20px;">
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada data pendidikan untuk PTK ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <a href="{{ route('pendidikan-ptk.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
</div>

@endsection
