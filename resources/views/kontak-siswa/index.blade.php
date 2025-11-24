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
    $isAdmin = $user->role === 'admin';
    $isSiswa = $user->role === 'siswa';
    $prefix = $isAdmin ? 'admin.' : 'siswa.';
@endphp

<div class="container">

    @if(auth()->user()->role === 'siswa')
        <div class="mb-3 d-flex flex-wrap gap-2">
            <a href="{{ route('siswa.siswa.index') }}" class="btn btn-primary">Siswa</a>
            <a href="{{ route('siswa.akun-siswa.index') }}" class="btn btn-primary">Akun</a>
            <a href="{{ route('siswa.dokumen-siswa.index') }}" class="btn btn-primary">Dokumen</a>
            <a href="{{ route('siswa.periodik.index') }}" class="btn btn-primary">Periodik</a>
            <a href="{{ route('siswa.beasiswa.index') }}" class="btn btn-primary">Beasiswa</a>
            <a href="{{ route('siswa.prestasi.index') }}" class="btn btn-primary">Prestasi</a>
            <a href="{{ route('siswa.orang-tua.index') }}" class="btn btn-primary">Orang Tua</a>
            <a href="{{ route('siswa.registrasi-siswa.index') }}" class="btn btn-primary">Registrasi</a>
            <a href="{{ route('siswa.kesejahteraan-siswa.index') }}" class="btn btn-primary">Kesejahteraan</a>
            <a href="{{ route('siswa.kontak-siswa.index') }}" class="btn btn-primary">Kontak & Alamat</a>
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

    @if(!$isSiswa)
        <div class="d-flex justify-content-start align-items-center mb-3" style="gap: 0.5rem;">
            <input type="text" id="search" class="form-control form-control-sm" placeholder="Cari Nama Siswa..." style="max-width: 200px;">
            <select id="rombelFilter" class="form-control form-control-sm" style="max-width: 200px;">
                <option value="">Semua Rombel</option>
                @foreach($rombels as $rombel)
                    <option value="{{ $rombel->id }}">{{ $rombel->nama_rombel }}</option>
                @endforeach
            </select>
        </div>
    @else
        <div class="d-flex mb-3">
            <a href="{{ route($prefix.'kontak-siswa.edit', ['kontak_siswa' => $data[0]->siswa_id ?? Auth::user()->id]) }}"
               class="btn btn-primary px-4"
               style="background: linear-gradient(180deg, #0770d3, #007efd, #55a6f8); color: white; border-radius: 6px;">
                <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
        </div>
    @endif

    <div class="table-responsive rounded-3 overflow-hidden mt-3">
        <table class="table table-bordered" id="kontakTable">
            <thead class="text-white">
                <tr>
                    @if(!$isSiswa)
                        <th style="width:50px;">No</th>
                        <th>Nama Siswa</th>
                        <th>No HP</th>
                        <th>Email</th>
                        <th>Alamat Jalan</th>
                        <th>RT</th>
                        <th>RW</th>
                        <th>Kelurahan</th>
                        <th>Kecamatan</th>
                        <th style="width:80px;">Aksi</th>
                    @else
                        <th>Data Kontak & Alamat</th>
                        <th>Keterangan</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @foreach($data as $index => $item)
                    @if(!$isSiswa)
                        <tr data-rombel="{{ $item->rombel_id ?? '' }}">
                            <td>{{ $data->firstItem() + $index }}</td>
                            <td class="nama_siswa">{{ $item->nama_lengkap ?? '-' }}</td>
                            <td>{{ $item->no_hp ?? '-' }}</td>
                            <td>{{ $item->email ?? '-' }}</td>
                            <td>{{ $item->alamat_jalan ?? '-' }}</td>
                            <td>{{ $item->rt ?? '-' }}</td>
                            <td>{{ $item->rw ?? '-' }}</td>
                            <td>{{ $item->kelurahan ?? '-' }}</td>
                            <td>{{ $item->kecamatan ?? '-' }}</td>
                            <td>
                                <a href="{{ route($prefix.'kontak-siswa.edit', ['kontak_siswa' => $item->siswa_id]) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px; height:20px;">
                                </a>
                                @if($item->id)
                                    <form action="{{ route($prefix.'kontak-siswa.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-no-border"
                                            onclick="return confirm('Yakin ingin menghapus data kontak siswa ini?')">
                                            <img src="{{ asset('images/delete.png') }}" alt="Hapus" style="width:20px; height:20px;">
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>Kontak</td>
                            <td>
                                <strong>No HP:</strong> {{ $item->no_hp ?? '-' }}<br>
                                <strong>Email:</strong> {{ $item->email ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>
                                <strong>Alamat Jalan:</strong> {{ $item->alamat_jalan ?? '-' }}<br>
                                <strong>RT:</strong> {{ $item->rt ?? '-' }}<br>
                                <strong>RW:</strong> {{ $item->rw ?? '-' }}<br>
                                <strong>Kelurahan:</strong> {{ $item->kelurahan ?? '-' }}<br>
                                <strong>Kecamatan:</strong> {{ $item->kecamatan ?? '-' }}<br>
                                <strong>Kode Pos:</strong> {{ $item->kode_pos ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td>Lainnya</td>
                            <td>
                                <strong>Tempat Tinggal:</strong> {{ $item->tempat_tinggal ?? '-' }}<br>
                                <strong>Moda Transportasi:</strong> {{ $item->moda_transportasi ?? '-' }}<br>
                                <strong>Anak Ke-:</strong> {{ $item->anak_ke ?? '-' }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    @if(!$isSiswa)
        <div class="d-flex justify-content-center mt-3">
            {{ $data->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>

@if(!$isSiswa)
<script>
const searchInput = document.getElementById('search');
const rombelSelect = document.getElementById('rombelFilter');
const rows = document.querySelectorAll('#kontakTable tbody tr');

function filterTable() {
    const searchValue = searchInput.value.toLowerCase();
    const rombelValue = rombelSelect.value;

    rows.forEach(row => {
        const namaCell = row.querySelector('.nama_siswa');
        if (!namaCell) return;

        const nama = namaCell.textContent.toLowerCase();
        const rombel = row.getAttribute('data-rombel');

        row.style.display = (nama.includes(searchValue) && (rombelValue === '' || rombel === rombelValue)) ? '' : 'none';
    });
}

searchInput.addEventListener('keyup', filterTable);
rombelSelect.addEventListener('change', filterTable);
</script>
@endif

@endsection
