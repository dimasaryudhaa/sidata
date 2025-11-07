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

    @if($isSiswa)
        <div class="d-flex justify-start-end mb-3">
            <a href="{{ route('periodik.edit', $periodik->first()->id ?? 0) }}"
            class="btn btn-primary px-4"
            style="background: linear-gradient(180deg, #0770d3, #007efd, #55a6f8); color: white; border-radius: 6px;">
                <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
        </div>
    @else
        <div class="d-flex justify-content-start mb-3" style="gap:0.5rem;">
            <input type="text" id="search" class="form-control form-control-sm" placeholder="Cari Nama Siswa..." style="max-width: 200px;">
            <select id="rombelFilter" class="form-control form-control-sm" style="max-width: 200px;">
                <option value="">Semua Rombel</option>
                @foreach($rombels as $rombel)
                    <option value="{{ $rombel->id }}">{{ $rombel->nama_rombel }}</option>
                @endforeach
            </select>
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
        <table class="table table-bordered" id="periodikTable">
            <thead class="text-white" style="background:linear-gradient(180deg,#0770d3,#007efd,#55a6f8);">
                <tr>
                    @if(!$isSiswa)
                        <th style="width:50px;">No</th>
                        <th>Nama Siswa</th>
                        <th>Tinggi Badan (cm)</th>
                        <th>Berat Badan (kg)</th>
                        <th>Lingkar Kepala (cm)</th>
                        <th>Jarak ke Sekolah (km)</th>
                        <th style="width:80px;">Aksi</th>
                    @else
                        <th style="width:50px;">No</th>
                        <th>Data Periodik</th>
                        <th>Keterangan</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @foreach($periodik as $index => $p)
                    @if(!$isSiswa)
                        <tr data-rombel="{{ $p->rombel_id ?? '' }}">
                            <td>{{ $periodik->firstItem() + $index }}</td>
                            <td class="nama_siswa">{{ $p->nama_lengkap ?? '-' }}</td>
                            <td>{{ $p->tinggi_badan_cm ? number_format($p->tinggi_badan_cm, 0) : '-' }}</td>
                            <td>{{ $p->berat_badan_kg ? number_format($p->berat_badan_kg, 0) : '-' }}</td>
                            <td>{{ $p->lingkar_kepala_cm ? number_format($p->lingkar_kepala_cm, 0) : '-' }}</td>
                            <td>{{ $p->jarak_sebenarnya_km ? number_format($p->jarak_sebenarnya_km, 1) . ' km' : '-' }}</td>
                            <td>
                                <a href="{{ route('periodik.edit', ['periodik' => $p->siswa_id]) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px; height:20px;">
                                </a>
                                @if($p->periodik_id)
                                    <form action="{{ route('periodik.destroy', $p->periodik_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-no-border"
                                            onclick="return confirm('Yakin ingin menghapus data periodik siswa ini?')">
                                            <img src="{{ asset('images/delete.png') }}" alt="Hapus" style="width:20px; height:20px;">
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ $index * 3 + 1 }}</td>
                            <td>Data Fisik</td>
                            <td>
                                <strong>Tinggi Badan:</strong> {{ $p->tinggi_badan_cm ? number_format($p->tinggi_badan_cm, 0) . ' cm' : '-' }}<br>
                                <strong>Berat Badan:</strong> {{ $p->berat_badan_kg ? number_format($p->berat_badan_kg, 0) . ' kg' : '-' }}<br>
                                <strong>Lingkar Kepala:</strong> {{ $p->lingkar_kepala_cm ? number_format($p->lingkar_kepala_cm, 0) . ' cm' : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td>{{ $index * 3 + 2 }}</td>
                            <td>Jarak & Waktu Tempuh</td>
                            <td>
                                <strong>Jarak ke Sekolah:</strong> {{ $p->jarak_ke_sekolah ?? '-' }}<br>
                                <strong>Jarak Sebenarnya:</strong> {{ $p->jarak_sebenarnya_km ? number_format($p->jarak_sebenarnya_km, 1) . ' km' : '-' }}<br>
                                <strong>Waktu Tempuh:</strong>
                                @if($p->waktu_tempuh_jam || $p->waktu_tempuh_menit)
                                    {{ $p->waktu_tempuh_jam ? $p->waktu_tempuh_jam . ' jam ' : '' }}
                                    {{ $p->waktu_tempuh_menit ? $p->waktu_tempuh_menit . ' menit' : '' }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>{{ $index * 3 + 3 }}</td>
                            <td>Data Keluarga</td>
                            <td>
                                <strong>Jumlah Saudara:</strong> {{ $p->jumlah_saudara ?? '-' }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $periodik->links('pagination::bootstrap-5') }}
    </div>

</div>


@if(!$isSiswa)
<script>
    const searchInput = document.getElementById('search');
    const rombelSelect = document.getElementById('rombelFilter');
    const rows = document.querySelectorAll('#periodikTable tbody tr');

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
</script>
@endif

@endsection
