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
@endphp

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
            <table class="table table-bordered" id="sertifikatPtkTable">
                <thead class="text-white">
                    <tr>
                        <th style="width:60px;">No</th>
                        <th>Jenis Sertifikasi</th>
                        <th>Nomor Sertifikat</th>
                        <th>Tahun Sertifikasi</th>
                        <th>Bidang Studi</th>
                        <th>NRG</th>
                        <th>Nomor Peserta</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sertifikatPtk as $index => $item)
                        <tr>
                            <td>{{ $sertifikatPtk->firstItem() + $index }}</td>
                            <td>{{ $item->jenis_sertifikasi ?? '-' }}</td>
                            <td>{{ $item->nomor_sertifikat ?? '-' }}</td>
                            <td>{{ $item->tahun_sertifikasi ?? '-' }}</td>
                            <td>{{ $item->bidang_studi ?? '-' }}</td>
                            <td>{{ $item->nrg ?? '-' }}</td>
                            <td>{{ $item->nomor_peserta ?? '-' }}</td>
                            <td>
                                <a href="{{ route('sertifikat-ptk.edit', ['sertifikat_ptk' => $item->sertifikat_id]) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px; height:20px;">
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $sertifikatPtk->links('pagination::bootstrap-5') }}
        </div>

    @else
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form class="d-flex mb-3" style="gap:0.5rem;">
                <input type="text" id="search" class="form-control form-control-sm"
                       placeholder="Cari Nama PTK..." style="max-width:250px;">
            </form>
        </div>

        <div class="table-responsive rounded-3 overflow-hidden mt-3">
            <table class="table table-bordered" id="sertifikatPtkTable">
                <thead class="text-white">
                    <tr>
                        <th style="width:50px;">No</th>
                        <th style="width:500px;">Nama PTK</th>
                        <th>Jumlah Sertifikat</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sertifikatPtk as $index => $item)
                        <tr>
                            <td>{{ $sertifikatPtk->firstItem() + $index }}</td>
                            <td class="nama_ptk">{{ $item->nama_lengkap ?? '-' }}</td>
                            <td>{{ $item->jumlah_sertifikat ?? 0 }}</td>
                            <td>
                                <a href="{{ route('sertifikat-ptk.show', $item->ptk_id) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/view.png') }}" alt="Lihat Sertifikat" style="width:20px; height:20px;">
                                </a>
                                <a href="{{ route('sertifikat-ptk.create', ['ptk_id' => $item->ptk_id]) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/tambah2.png') }}" alt="Tambah Sertifikat" style="width:20px; height:20px;">
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $sertifikatPtk->links('pagination::bootstrap-5') }}
        </div>

        <script>
            document.getElementById('search').addEventListener('keyup', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#sertifikatPtkTable tbody tr');

                rows.forEach(row => {
                    let nama = row.querySelector('.nama_ptk').textContent.toLowerCase();
                    row.style.display = nama.includes(filter) ? '' : 'none';
                });
            });
        </script>
    @endif
</div>

@endsection
