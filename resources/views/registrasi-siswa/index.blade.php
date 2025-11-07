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
}
</style>

@php
    $user = Auth::user();
    $isSiswa = $user->role === 'siswa';
@endphp

<div class="container">
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
    @else
        <div class="d-flex justify-content-start align-items-center mb-3">
            <a href="{{ route('registrasi-siswa.edit', ['registrasi_siswa' => $data[0]->siswa_id ?? Auth::user()->id]) }}"
               class="btn btn-primary px-4"
               style="background: linear-gradient(180deg, #0770d3, #007efd, #55a6f8); color: white; border-radius: 6px;">
                <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
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

    <div class="table-responsive rounded-3 overflow-hidden mt-3">
    <table class="table table-bordered" id="registrasiTable">
        <thead class="text-white" style="background:linear-gradient(180deg,#0770d3,#007efd,#55a6f8);">
            <tr>
                @if(!$isSiswa)
                    <th style="width:50px;">No</th>
                    <th>Nama Siswa</th>
                    <th>Jenis Pendaftaran</th>
                    <th>Tanggal Masuk</th>
                    <th>Sekolah Asal</th>
                    <th style="width:80px;">Aksi</th>
                @else
                    <th style="width:50px;">No</th>
                    <th>Data Registrasi</th>
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
                        <td>{{ $item->jenis_pendaftaran ?? '-' }}</td>
                        <td>{{ $item->tanggal_masuk ?? '-' }}</td>
                        <td>{{ $item->sekolah_asal ?? '-' }}</td>
                        <td>
                            <a href="{{ route('registrasi-siswa.edit', ['registrasi_siswa' => $item->siswa_id]) }}" class="btn btn-sm btn-no-border">
                                <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px;height:20px;">
                            </a>

                            @if($item->registrasi_id)
                                <form action="{{ route('registrasi-siswa.destroy', $item->registrasi_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-no-border"
                                        onclick="return confirm('Yakin ingin menghapus data registrasi siswa ini?')">
                                        <img src="{{ asset('images/delete.png') }}" alt="Hapus" style="width:20px;height:20px;">
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>{{ $index * 3 + 1 }}</td>
                        <td>Informasi Pendaftaran</td>
                        <td>
                            <strong>Jenis Pendaftaran:</strong> {{ $item->jenis_pendaftaran ?? '-' }}<br>
                            <strong>Tanggal Masuk:</strong> {{ $item->tanggal_masuk ?? '-' }}<br>
                            <strong>Sekolah Asal:</strong> {{ $item->sekolah_asal ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $index * 3 + 2 }}</td>
                        <td>Nomor Dokumen</td>
                        <td>
                            <strong>No Peserta UN:</strong> {{ $item->no_peserta_un ?? '-' }}<br>
                            <strong>No Seri Ijazah:</strong> {{ $item->no_seri_ijazah ?? '-' }}<br>
                            <strong>No SKHUN:</strong> {{ $item->no_skhun ?? '-' }}
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $data->links() }}
    </div>
</div>

@if(!$isSiswa)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search');
    const rombelSelect = document.getElementById('rombelFilter');
    const rows = document.querySelectorAll('#registrasiTable tbody tr');

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const rombelValue = rombelSelect.value;

        rows.forEach(row => {
            const nama = row.querySelector('.nama_siswa')?.textContent.toLowerCase() || '';
            const rombel = row.getAttribute('data-rombel');
            const matchesNama = nama.includes(searchValue);
            const matchesRombel = rombelValue === '' || rombel === rombelValue;
            row.style.display = (matchesNama && matchesRombel) ? '' : 'none';
        });
    }

    searchInput.addEventListener('keyup', filterTable);
    rombelSelect.addEventListener('change', filterTable);
});
</script>
@endif


@endsection

