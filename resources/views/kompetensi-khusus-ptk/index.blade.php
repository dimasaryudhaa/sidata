@extends('layouts.app')

@section('content')

@php
    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    $isPtk = $user->role === 'ptk';
    $prefix = $isAdmin ? 'admin.' : 'ptk.';
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

    @if($isPtk)
        <div class="table-responsive rounded-3 overflow-hidden mt-3">
            <table class="table table-bordered">
                <thead class="text-white">
                    <tr>
                        <th style="width:60px;">No</th>
                        <th>Punya Lisensi Kepala Sekolah</th>
                        <th>Nomor Unik Kepala Sekolah</th>
                        <th>Keahlian Lab/Oratorium</th>
                        <th>Mampu Menangani</th>
                        <th>Keahlian Braile</th>
                        <th>Keahlian Bahasa Isyarat</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kompetensiKhusus as $index => $item)
                        <tr>
                            <td>{{ $kompetensiKhusus->firstItem() + $index }}</td>
                            <td>{{ $item->punya_lisensi_kepala_sekolah ? 'Ya' : 'Tidak' }}</td>
                            <td>{{ $item->nomor_unik_kepala_sekolah ?? '-' }}</td>
                            <td>{{ $item->keahlian_lab_oratorium ?? '-' }}</td>
                            <td>{{ $item->mampu_menangani ?? '-' }}</td>
                            <td>{{ $item->keahlian_braile ?? '-' }}</td>
                            <td>{{ $item->keahlian_bahasa_isyarat ?? '-' }}</td>
                            <td>
                                <a href="{{ route($prefix.'kompetensi-khusus-ptk.edit', $item->kompetensi_khusus_id) }}"
                                   class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px; height:20px;">
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $kompetensiKhusus->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="d-flex justify-content-between align-items-center mb-3">
            <input type="text" id="search" class="form-control form-control-sm" placeholder="Cari Nama PTK..." style="max-width:250px;">
        </div>
        <div class="table-responsive rounded-3 overflow-hidden mt-3">
            <table class="table table-bordered" id="kompetensiKhususTable">
                <thead class="text-white">
                    <tr>
                        <th style="width:50px;">No</th>
                        <th style="width:500px;">Nama PTK</th>
                        <th>Jumlah Kompetensi Khusus</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kompetensiKhusus as $index => $item)
                        <tr>
                            <td>{{ $kompetensiKhusus->firstItem() + $index }}</td>
                            <td class="nama_ptk">{{ $item->nama_lengkap ?? '-' }}</td>
                            <td>{{ $item->jumlah_kompetensi_khusus ?? 0 }}</td>
                            <td>
                                <a href="{{ route($prefix.'kompetensi-khusus-ptk.show', $item->ptk_id) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/view.png') }}" alt="Lihat Kompetensi Khusus" style="width:20px; height:20px;">
                                </a>
                                <a href="{{ route($prefix.'kompetensi-khusus-ptk.create', ['ptk_id' => $item->ptk_id]) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/tambah2.png') }}" alt="Tambah Kompetensi Khusus" style="width:20px; height:20px;">
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $kompetensiKhusus->links('pagination::bootstrap-5') }}
        </div>

        <script>
            document.getElementById('search').addEventListener('keyup', function() {
                let filter = this.value.toLowerCase();
                document.querySelectorAll('#kompetensiKhususTable tbody tr').forEach(row => {
                    let nama = row.querySelector('.nama_ptk').textContent.toLowerCase();
                    row.style.display = nama.includes(filter) ? '' : 'none';
                });
            });
        </script>
    @endif

</div>

@endsection
