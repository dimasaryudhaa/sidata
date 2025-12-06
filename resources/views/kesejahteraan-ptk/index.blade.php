@extends('layouts.app')

@section('content')

@php
    use Illuminate\Support\Facades\Auth;

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

    @if($isPtk)
        <div class="table-responsive rounded-3 mt-3">
            <table class="table table-bordered">
                <thead class="text-white">
                    <tr>
                        <th style="width:60px;">No</th>
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
                            <td>{{ $kesejahteraan->firstItem() + $index }}</td>
                            <td>{{ $item->jenis_kesejahteraan ?? '-' }}</td>
                            <td>{{ $item->nama ?? '-' }}</td>
                            <td>{{ $item->penyelenggara ?? '-' }}</td>
                            <td>{{ $item->dari_tahun ?? '-' }}</td>
                            <td>{{ $item->sampai_tahun ?? '-' }}</td>
                            <td>{{ $item->status ?? '-' }}</td>
                            <td>
                                <a href="{{ route($prefix.'kesejahteraan-ptk.edit', ['kesejahteraan_ptk' => $item->kesejahteraan_id]) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px; height:20px;">
                                </a>

                                @if($isAdmin)
                                <form action="{{ route($prefix.'kesejahteraan-ptk.destroy', ['kesejahteraan_ptk' => $item->kesejahteraan_id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-no-border"
                                        onclick="return confirm('Yakin ingin menghapus data kesejahteraan ini?')">
                                        <img src="{{ asset('images/delete.png') }}" alt="Hapus" style="width:20px; height:20px;">
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada data kesejahteraan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $kesejahteraan->links('pagination::bootstrap-5') }}
            </div>
        </div>

    @else
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form class="d-flex mb-3" style="gap:0.5rem;">
                <input type="text" id="search" class="form-control form-control-sm"
                       placeholder="Cari Nama PTK..." style="max-width: 250px;">
            </form>
        </div>

        <div class="table-responsive rounded-3 overflow-hidden mt-3">
            <table class="table table-bordered" id="kesejahteraanTable">
                <thead class="text-white">
                    <tr>
                        <th style="width:50px;">No</th>
                        <th style="width:500px;">Nama PTK</th>
                        <th>Jumlah Kesejahteraan</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kesejahteraan as $index => $item)
                        <tr>
                            <td>{{ $kesejahteraan->firstItem() + $index }}</td>
                            <td class="nama_ptk">{{ $item->nama_lengkap ?? '-' }}</td>
                            <td>{{ $item->jumlah_kesejahteraan ?? 0 }}</td>
                            <td>
                                <a href="{{ route($prefix.'kesejahteraan-ptk.show', ['kesejahteraan_ptk' => $item->kesejahteraan_id ?? $item->ptk_id]) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/view.png') }}" alt="Lihat Data" style="width:20px; height:20px;">
                                </a>

                                <a href="{{ route($prefix.'kesejahteraan-ptk.create', ['ptk_id' => $item->ptk_id]) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/tambah2.png') }}" alt="Tambah Data" style="width:20px; height:20px;">
                                </a>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $kesejahteraan->links('pagination::bootstrap-5') }}
        </div>

        <script>
        const searchInput = document.getElementById('search');
        const tbody = document.querySelector('#kesejahteraanTable tbody');

        searchInput.addEventListener('keyup', function () {
            let query = this.value.trim();

            if (query.length === 0) {
                location.reload();
                return;
            }

            fetch(`/{{ $isPtk ? 'ptk' : 'admin' }}/kesejahteraan-ptk/search?q=` + query)
                .then(res => res.json())
                .then(data => {
                    tbody.innerHTML = '';

                    data.forEach((item, index) => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${index + 1}</td>
                                <td class="nama_ptk">${item.nama_lengkap ?? '-'}</td>
                                <td>${item.jumlah_kesejahteraan ?? 0}</td>
                                <td>
                                    <a href="/{{ $isPtk ? 'ptk' : 'admin' }}/kesejahteraan-ptk/${item.kesejahteraan_id ?? item.ptk_id}"
                                        class="btn btn-sm btn-no-border">
                                        <img src="/images/view.png" style="width:20px;">
                                    </a>

                                    <a href="/{{ $isPtk ? 'ptk' : 'admin' }}/kesejahteraan-ptk/create?ptk_id=${item.ptk_id}"
                                        class="btn btn-sm btn-no-border">
                                        <img src="/images/tambah2.png" style="width:20px;">
                                    </a>
                                </td>
                            </tr>
                        `;
                    });
                });
        });
        </script>

    @endif

</div>

@endsection
