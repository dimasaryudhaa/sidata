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

    .badge {
        font-size: 0.85rem;
        padding: 6px 10px;
    }

    .card {
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border-radius: 12px;
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
                        <a href="{{ route('kesejahteraan-ptk.edit', $item->id) }}" class="btn btn-sm btn-no-border">
                            <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px; height:20px;">
                        </a>
                        <form action="{{ route('kesejahteraan-ptk.destroy', $item->id) }}" method="POST" class="d-inline"
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
    <a href="{{ route('kesejahteraan-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
</div>

@endsection
