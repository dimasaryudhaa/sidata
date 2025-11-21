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

@php
    $user = Auth::user();
    $isPtk = $user->role === 'ptk';
    $prefix = $isPtk ? 'ptk.' : 'admin.';
@endphp

<div class="container">

    @if(auth()->user()->role === 'ptk')
        <div class="mb-3 d-flex flex-wrap gap-2">
            <a href="{{ route('ptk.penugasan-ptk.index') }}" class="btn btn-primary">Penugasan</a>
            <a href="{{ route('ptk.kepegawaian-ptk.index') }}" class="btn btn-primary">Kepegawaian</a>
            <a href="{{ route('ptk.tugas-tambahan.index') }}" class="btn btn-primary">Tugas Tambahan</a>
            <a href="{{ route('ptk.riwayat-gaji.index') }}" class="btn btn-primary">Riwayat Gaji</a>
            <a href="{{ route('ptk.riwayat-karir.index') }}" class="btn btn-primary">Riwayat Karir</a>
            <a href="{{ route('ptk.riwayat-jabatan.index') }}" class="btn btn-primary">Riwayat Jabatan</a>
            <a href="{{ route('ptk.riwayat-kepangkatan.index') }}" class="btn btn-primary">Riwayat Kepangkatan</a>
            <a href="{{ route('ptk.riwayat-jabatan-fungsional.index') }}" class="btn btn-primary">Riwayat Jabatan Fungsional</a>
        </div>
    @endif

    @if(session('success'))
        <div id="successAlert"
            class="position-fixed top-50 start-50 translate-middle bg-white text-center p-4 rounded shadow-lg border"
            style="z-index:1050; min-width:320px;">
            <div class="d-flex justify-content-center mb-3">
                <div class="d-flex justify-content-center align-items-center"
                    style="width:80px; height:80px; background-color:#d4edda; border-radius:50%;">
                    <i class="bi bi-check-lg text-success" style="font-size:2.5rem;"></i>
                </div>
            </div>
            <h5 class="fw-bold mb-1">Success</h5>
            <p class="text-muted mb-0">{{ session('success') }}</p>
        </div>

        <script>
            setTimeout(() => {
                const alertBox = document.getElementById('successAlert');
                if (alertBox) {
                    alertBox.style.transition = 'opacity 0.5s ease';
                    alertBox.style.opacity = '0';
                    setTimeout(() => alertBox.remove(), 500);
                }
            }, 5000);
        </script>
    @endif

    @if(!$isPtk)

        <div class="d-flex justify-content-between align-items-center mb-3">
            <form class="d-flex mb-3" style="gap:0.5rem;">
                <input type="text" id="search" class="form-control form-control-sm"
                    placeholder="Cari Nama PTK..." style="max-width: 250px;">
            </form>
        </div>

        <div class="table-responsive rounded-3 overflow-hidden mt-3">
            <table class="table table-bordered" id="penugasanPtkTable">
                <thead class="text-white">
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Nama PTK</th>
                        <th>Nomor Surat Tugas</th>
                        <th>Tanggal Surat Tugas</th>
                        <th>TMT Tugas</th>
                        <th>Status Sekolah Induk</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                        <tr>
                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                            <td class="nama_lengkap">{{ $item->nama_lengkap ?? '-' }}</td>
                            <td>{{ $item->nomor_surat_tugas ?? '-' }}</td>
                            <td>{{ $item->tanggal_surat_tugas ?? '-' }}</td>
                            <td>{{ $item->tmt_tugas ?? '-' }}</td>
                            <td>{{ $item->status_sekolah_induk ?? '-' }}</td>

                            <td>
                                @if($item->penugasan_id)
                                    <a href="{{ route($prefix.'penugasan-ptk.edit', ['penugasan_ptk' => $item->penugasan_id]) }}"
                                        class="btn btn-sm btn-no-border">
                                        <img src="{{ asset('images/edit.png') }}" style="width:20px; height:20px;">
                                    </a>

                                    <form action="{{ route($prefix.'penugasan-ptk.destroy', $item->penugasan_id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-no-border"
                                            onclick="return confirm('Yakin ingin menghapus data penugasan PTK ini?')">
                                            <img src="{{ asset('images/delete.png') }}" style="width:20px; height:20px;">
                                        </button>
                                    </form>

                                @else
                                    <a href="{{ route($prefix.'penugasan-ptk.edit', ['penugasan_ptk' => $item->ptk_id]) }}"
                                        class="btn btn-sm btn-no-border">
                                        <img src="{{ asset('images/edit.png') }}" style="width:20px; height:20px;">
                                    </a>

                                    <button class="btn btn-sm btn-no-border" disabled>
                                        <img src="{{ asset('images/delete.png') }}" style="width:20px; height:20px; opacity:0.5;">
                                    </button>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $data->links('pagination::bootstrap-5') }}
        </div>

        <script>
            document.getElementById('search').addEventListener('keyup', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#penugasanPtkTable tbody tr');

                rows.forEach(row => {
                    let nama = row.querySelector('.nama_lengkap').textContent.toLowerCase();
                    row.style.display = nama.includes(filter) ? '' : 'none';
                });
            });
        </script>

    @else

        @php $dataPtk = $data->first(); @endphp

        @if($dataPtk && $dataPtk->penugasan_id)
            <div class="d-flex justify-content-start align-items-center mb-3">
                <a href="{{ route($prefix.'penugasan-ptk.edit', ['penugasan_ptk' => $dataPtk->penugasan_id]) }}"
                    class="btn btn-primary px-4"
                    style="background: linear-gradient(180deg, #0770d3, #007efd, #55a6f8); color: white; border-radius: 6px;">
                    <i class="bi bi-pencil-square me-2"></i> Edit
                </a>
            </div>
        @endif

        <div class="table-responsive rounded-3 overflow-hidden mt-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Data Penugasan</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Nomor Surat Tugas</td>
                        <td>{{ $dataPtk->nomor_surat_tugas ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Surat Tugas</td>
                        <td>{{ $dataPtk->tanggal_surat_tugas ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>TMT Tugas</td>
                        <td>{{ $dataPtk->tmt_tugas ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Status Sekolah Induk</td>
                        <td>{{ $dataPtk->status_sekolah_induk ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    @endif

</div>

@endsection
