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
    $isSiswa = $user->role === 'siswa';
@endphp

<div class="container">

    {{-- Filter hanya untuk admin --}}
    @if(!$isSiswa)
    <div class="d-flex justify-content-start align-items-center mb-3" style="gap: 0.5rem;">
        <input type="text" id="search" class="form-control form-control-sm" placeholder="Cari Nama Siswa" style="max-width: 200px;">
        <select id="rombelFilter" class="form-control form-control-sm" style="max-width: 200px;">
            <option value="">Semua Rombel</option>
            @foreach($rombels as $rombel)
                <option value="{{ $rombel->id }}">{{ $rombel->nama_rombel }}</option>
            @endforeach
        </select>
    </div>
    @endif

    {{-- Alert sukses --}}
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

    {{-- Tabel --}}
    <div class="table-responsive rounded-3 overflow-hidden mt-3">
        <table class="table table-bordered" id="beasiswaTable">
            <thead class="text-white">
                <tr>
                    <th style="width: 50px;">No</th>
                    @if($isSiswa)
                        <th>Jenis Beasiswa</th>
                        <th>Keterangan</th>
                        <th>Tahun Mulai</th>
                        <th>Tahun Selesai</th>
                        <th style="width: 80px;">Aksi</th>
                    @else
                        <th>Nama Siswa</th>
                        <th>Jumlah Beasiswa</th>
                        <th style="width: 80px;">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($beasiswa as $index => $b)
                <tr @if(!$isSiswa) data-rombel="{{ $b->rombel_id }}" @endif>
                    <td>{{ $beasiswa->firstItem() + $index }}</td>

                    @if($isSiswa)
                        <td>{{ $b->jenis_beasiswa }}</td>
                        <td>{{ $b->keterangan }}</td>
                        <td>{{ $b->tahun_mulai }}</td>
                        <td>{{ $b->tahun_selesai }}</td>
                        <td>
                            <a href="{{ route('beasiswa.edit', $b->id) }}" class="btn btn-sm btn-no-border">
                                <img src="{{ asset('images/edit.png') }}" alt="Edit Beasiswa" style="width:20px; height:20px;">
                            </a>
                        </td>
                    @else
                        <td class="nama_siswa">{{ $b->nama_lengkap ?? '-' }}</td>
                        <td>{{ $b->jumlah_beasiswa }}</td>
                        <td>
                            <a href="{{ route('beasiswa.show', $b->siswa_id) }}" class="btn btn-sm btn-no-border">
                                <img src="{{ asset('images/view.png') }}" alt="Lihat Beasiswa" style="width:20px; height:20px;">
                            </a>
                            <a href="{{ route('beasiswa.create', ['siswa_id' => $b->siswa_id]) }}" class="btn btn-sm btn-no-border">
                                <img src="{{ asset('images/tambah2.png') }}" alt="Tambah Beasiswa" style="width:20px; height:20px;">
                            </a>
                        </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $beasiswa->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- Filter admin --}}
@if(!$isSiswa)
<script>
const searchInput = document.getElementById('search');
const rombelSelect = document.getElementById('rombelFilter');
const rows = document.querySelectorAll('#beasiswaTable tbody tr');

function filterTable() {
    const searchValue = searchInput.value.toLowerCase();
    const rombelValue = rombelSelect.value;

    rows.forEach(row => {
        const nama = row.querySelector('.nama_siswa').textContent.toLowerCase();
        const rombel = row.getAttribute('data-rombel');

        const matchesNama = nama.includes(searchValue);
        const matchesRombel = rombelValue === '' || rombel === rombelValue;

        row.style.display = (matchesNama && matchesRombel) ? '' : 'none';
    });
}

searchInput.addEventListener('keyup', filterTable);
rombelSelect.addEventListener('change', filterTable);
</script>
@endif

@endsection
