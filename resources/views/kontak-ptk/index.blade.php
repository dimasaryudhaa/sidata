@extends('layouts.app')

@section('content')

<style>
    ::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }

    html {
        scrollbar-width: none;
    }

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
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $isPtk = $user->role === 'ptk';
    $prefix = $isPtk ? 'ptk.' : 'admin.';
@endphp

<div class="container">

    @if(auth()->user()->role === 'admin')
        <div class="mb-3 d-flex flex-wrap gap-2">
            <a href="{{ route('admin.ptk.index') }}" class="btn btn-primary">Ptk</a>
            <a href="{{ route('admin.akun-ptk.index') }}" class="btn btn-primary">Akun</a>
            <a href="{{ route('admin.kontak-ptk.index') }}" class="btn btn-primary">Kontak</a>
            <a href="{{ route('admin.dokumen-ptk.index') }}" class="btn btn-primary">Dokumen</a>
            <a href="{{ route('admin.anak-ptk.index') }}" class="btn btn-primary">Anak</a>
            <a href="{{ route('admin.keluarga-ptk.index') }}" class="btn btn-primary">Keluarga</a>
            <a href="{{ route('admin.tunjangan.index') }}" class="btn btn-primary">Tunjangan</a>
            <a href="{{ route('admin.kesejahteraan-ptk.index') }}" class="btn btn-primary">Kesejahteraan</a>
        </div>
    @endif

    @if(auth()->user()->role === 'ptk')
        <div class="mb-3 d-flex flex-wrap gap-2">
            <a href="{{ route('ptk.ptk.index') }}" class="btn btn-primary">Ptk</a>
            <a href="{{ route('ptk.akun-ptk.index') }}" class="btn btn-primary">Akun</a>
            <a href="{{ route('ptk.kontak-ptk.index') }}" class="btn btn-primary">Kontak</a>
            <a href="{{ route('ptk.dokumen-ptk.index') }}" class="btn btn-primary">Dokumen</a>
            <a href="{{ route('ptk.anak-ptk.index') }}" class="btn btn-primary">Anak</a>
            <a href="{{ route('ptk.keluarga-ptk.index') }}" class="btn btn-primary">Keluarga</a>
            <a href="{{ route('ptk.tunjangan.index') }}" class="btn btn-primary">Tunjangan</a>
            <a href="{{ route('ptk.kesejahteraan-ptk.index') }}" class="btn btn-primary">Kesejahteraan</a>
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
            }, 2000);
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
            <table class="table table-bordered" id="kontakPtkTable">
                <thead class="text-white">
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Nama PTK</th>
                        <th>No HP</th>
                        <th>Email</th>
                        <th>Alamat Jalan</th>
                        <th>Kelurahan</th>
                        <th>Kecamatan</th>
                        <th>Kode Pos</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $item)
                        <tr>
                            <td>{{ $data->firstItem() + $index }}</td>
                            <td class="nama_ptk">{{ $item->nama_lengkap ?? '-' }}</td>
                            <td>{{ $item->no_hp ?? '-' }}</td>
                            <td>{{ $item->email ?? '-' }}</td>
                            <td>{{ $item->alamat_jalan ?? '-' }}</td>
                            <td>{{ $item->kelurahan ?? '-' }}</td>
                            <td>{{ $item->kecamatan ?? '-' }}</td>
                            <td>{{ $item->kode_pos ?? '-' }}</td>
                            <td>
                                <a href="{{ route($prefix.'kontak-ptk.edit', ['kontak_ptk' => $item->kontak_id ?? $item->ptk_id]) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" style="width:20px; height:20px;">
                                </a>

                                @if($item->kontak_id)
                                    <form action="{{ route($prefix.'kontak-ptk.destroy', $item->kontak_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-no-border"
                                            onclick="return confirm('Yakin ingin menghapus data kontak PTK ini?')">
                                            <img src="{{ asset('images/delete.png') }}" style="width:20px; height:20px;">
                                        </button>
                                    </form>
                                @else
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

        <div class="mt-3" id="paginationBox">
            {{ $data->links('pagination::bootstrap-5') }}
        </div>

        <script>
        const searchInput = document.getElementById('search');
        const tbody = document.querySelector('#kontakPtkTable tbody');
        const paginationBox = document.getElementById('paginationBox');

        searchInput.addEventListener('keyup', function() {
            let query = this.value;

            if (query.length === 0) {
                location.reload();
                return;
            }

            fetch('/admin/kontak-ptk/search?q=' + query)
                .then(response => response.json())
                .then(data => {

                    paginationBox.style.display = "none";

                    tbody.innerHTML = '';

                    data.forEach((item, index) => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.nama_lengkap ?? '-'}</td>
                                <td>${item.no_hp ?? '-'}</td>
                                <td>${item.email ?? '-'}</td>
                                <td>${item.alamat_jalan ?? '-'}</td>
                                <td>${item.kelurahan ?? '-'}</td>
                                <td>${item.kecamatan ?? '-'}</td>
                                <td>${item.kode_pos ?? '-'}</td>

                                <td>
                                    <a href="/admin/kontak-ptk/${item.kontak_id ?? item.ptk_id}/edit"
                                        class="btn btn-sm btn-no-border">
                                        <img src="/images/edit.png" style="width:20px; height:20px;">
                                    </a>

                                    ${
                                        item.kontak_id
                                        ? `
                                            <form action="/admin/kontak-ptk/${item.kontak_id}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-no-border"
                                                    onclick="return confirm('Yakin ingin menghapus?')">
                                                    <img src="/images/delete.png" style="width:20px; height:20px;">
                                                </button>
                                            </form>
                                        `
                                        : `
                                            <button class="btn btn-sm btn-no-border" disabled>
                                                <img src="/images/delete.png" style="width:20px; height:20px; opacity:0.5;">
                                            </button>
                                        `
                                    }
                                </td>
                            </tr>
                        `;
                    });
                });
        });
        </script>

    @else
        @php $dataPtk = $data->first(); @endphp

        @if($dataPtk && $dataPtk->kontak_id)
            <div class="d-flex justify-content-start align-items-center mb-3">
                <a href="{{ route($prefix.'kontak-ptk.edit', $dataPtk->kontak_id) }}"
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
                        <th>Data Kontak & Alamat</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>No HP</td><td>{{ $dataPtk->no_hp ?? '-' }}</td></tr>
                    <tr><td>Email</td><td>{{ $dataPtk->email ?? '-' }}</td></tr>
                    <tr><td>Alamat Jalan</td><td>{{ $dataPtk->alamat_jalan ?? '-' }}</td></tr>
                    <tr><td>RT</td><td>{{ $dataPtk->rt ?? '-' }}</td></tr>
                    <tr><td>RW</td><td>{{ $dataPtk->rw ?? '-' }}</td></tr>
                    <tr><td>Kelurahan</td><td>{{ $dataPtk->kelurahan ?? '-' }}</td></tr>
                    <tr><td>Kecamatan</td><td>{{ $dataPtk->kecamatan ?? '-' }}</td></tr>
                    <tr><td>Kode Pos</td><td>{{ $dataPtk->kode_pos ?? '-' }}</td></tr>
                </tbody>
            </table>
        </div>
    @endif

</div>

@endsection
