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
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $isPtk = $user->role === 'ptk';
    $isAdmin = $user->role === 'admin';
    $prefix = $isAdmin ? 'admin.' : 'ptk.';
@endphp

<div class="container">

    @if(auth()->user()->role === 'admin')
        <div class="mb-3 d-flex flex-wrap gap-2">
            <a href="{{ route('admin.penugasan-ptk.index') }}" class="btn btn-primary">Penugasan</a>
            <a href="{{ route('admin.kepegawaian-ptk.index') }}" class="btn btn-primary">Kepegawaian</a>
            <a href="{{ route('admin.tugas-tambahan.index') }}" class="btn btn-primary">Tugas Tambahan</a>
            <a href="{{ route('admin.riwayat-gaji.index') }}" class="btn btn-primary">Riwayat Gaji</a>
            <a href="{{ route('admin.riwayat-karir.index') }}" class="btn btn-primary">Riwayat Karir</a>
            <a href="{{ route('admin.riwayat-jabatan.index') }}" class="btn btn-primary">Riwayat Jabatan</a>
            <a href="{{ route('admin.riwayat-kepangkatan.index') }}" class="btn btn-primary">Riwayat Kepangkatan</a>
            <a href="{{ route('admin.riwayat-jabatan-fungsional.index') }}" class="btn btn-primary">Riwayat Jabatan Fungsional</a>
        </div>
    @endif

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

    @if($isPtk)
        <div class="table-responsive rounded-3 overflow-hidden mt-3">
            <table class="table table-bordered" id="riwayatKarirTable">
                <thead class="text-white">
                    <tr>
                        <th style="width:60px;">No</th>
                        <th>Jenjang Pendidikan</th>
                        <th>Jenis Lembaga</th>
                        <th>Status Kepegawaian</th>
                        <th>Jenis PTK</th>
                        <th>Lembaga Pengangkat</th>
                        <th>No SK Kerja</th>
                        <th>TMT Kerja</th>
                        <th>TST Kerja</th>
                        <th>Tempat Kerja</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayatKarir as $index => $item)
                        <tr>
                            <td>{{ $riwayatKarir->firstItem() + $index }}</td>
                            <td>{{ $item->jenjang_pendidikan ?? '-' }}</td>
                            <td>{{ $item->jenis_lembaga ?? '-' }}</td>
                            <td>{{ $item->status_kepegawaian ?? '-' }}</td>
                            <td>{{ $item->jenis_ptk ?? '-' }}</td>
                            <td>{{ $item->lembaga_pengangkat ?? '-' }}</td>
                            <td>{{ $item->no_sk_kerja ?? '-' }}</td>
                            <td>{{ $item->tmt_kerja ?? '-' }}</td>
                            <td>{{ $item->tst_kerja ?? '-' }}</td>
                            <td>{{ $item->tempat_kerja ?? '-' }}</td>
                            <td>
                                <a href="{{ route($prefix.'riwayat-karir.edit', $item->riwayat_karir_id) }}"
                                   class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" style="width:20px; height:20px;">
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $riwayatKarir->links('pagination::bootstrap-5') }}
        </div>

    @else
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form class="d-flex mb-3" style="gap:0.5rem;">
                <input type="text" id="search" class="form-control form-control-sm"
                       placeholder="Cari Nama PTK..." style="max-width:250px;">
            </form>
        </div>

        <div class="table-responsive rounded-3 overflow-hidden mt-3">
            <table class="table table-bordered" id="riwayatKarirTable">
                <thead class="text-white">
                    <tr>
                        <th style="width:50px;">No</th>
                        <th style="width:500px;">Nama PTK</th>
                        <th>Jumlah Riwayat Karir</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayatKarir as $index => $item)
                        <tr>
                            <td>{{ $riwayatKarir->firstItem() + $index }}</td>
                            <td class="nama_ptk">{{ $item->nama_lengkap ?? '-' }}</td>
                            <td>{{ $item->jumlah_riwayat_karir ?? 0 }}</td>
                            <td>
                                <a href="{{ route($prefix.'riwayat-karir.show', $item->ptk_id) }}"
                                   class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/view.png') }}" style="width:20px; height:20px;">
                                </a>

                                <a href="{{ route($prefix.'riwayat-karir.create', ['ptk_id' => $item->ptk_id]) }}"
                                   class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/tambah2.png') }}" style="width:20px; height:20px;">
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $riwayatKarir->links('pagination::bootstrap-5') }}
        </div>

        <script>
        const searchInput = document.getElementById('search');
        const tbody = document.querySelector('#riwayatKarirTable tbody');

        searchInput.addEventListener('keyup', function () {
            let query = this.value.trim();

            if (query.length === 0) {
                location.reload();
                return;
            }

            fetch(`/{{ $isAdmin ? 'admin' : 'ptk' }}/riwayat-karir/search?q=` + query)
                .then(res => res.json())
                .then(data => {
                    tbody.innerHTML = '';

                    data.forEach((item, index) => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${index + 1}</td>
                                <td class="nama_ptk">${item.nama_lengkap ?? '-'}</td>
                                <td>${item.jumlah_riwayat_karir ?? 0}</td>
                                <td>
                                    <a href="/{{ $isAdmin ? 'admin' : 'ptk' }}/riwayat-karir/${item.ptk_id}"
                                        class="btn btn-sm btn-no-border">
                                        <img src="/images/view.png" style="width:20px; height:20px;">
                                    </a>

                                    <a href="/{{ $isAdmin ? 'admin' : 'ptk' }}/riwayat-karir/create?ptk_id=${item.ptk_id}"
                                        class="btn btn-sm btn-no-border">
                                        <img src="/images/tambah2.png" style="width:20px; height:20px;">
                                    </a>
                                </td>
                            </tr>
                        `;
                    });
                })
                .catch(err => console.error(err));
        });
        </script>


    @endif

</div>

@endsection
