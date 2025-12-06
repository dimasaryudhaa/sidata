@extends('layouts.app')

@section('content')

@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    $isSiswa = $user->role === 'siswa';
    $prefix = $isAdmin ? 'admin.' : 'siswa.';
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

.btn-no-border:hover,
.btn-no-border:focus,
.btn-no-border:active {
    border: none !important;
    background: transparent !important;
}
</style>

<div class="container">

    @if(auth()->user()->role === 'admin')
        <div class="mb-3 d-flex flex-wrap gap-2">
            <a href="{{ route('admin.siswa.index') }}" class="btn btn-primary">Siswa</a>
            <a href="{{ route('admin.akun-siswa.index') }}" class="btn btn-primary">Akun</a>
            <a href="{{ route('admin.dokumen-siswa.index') }}" class="btn btn-primary">Dokumen</a>
            <a href="{{ route('admin.periodik.index') }}" class="btn btn-primary">Periodik</a>
            <a href="{{ route('admin.beasiswa.index') }}" class="btn btn-primary">Beasiswa</a>
            <a href="{{ route('admin.prestasi.index') }}" class="btn btn-primary">Prestasi</a>
            <a href="{{ route('admin.orang-tua.index') }}" class="btn btn-primary">Orang Tua</a>
            <a href="{{ route('admin.registrasi-siswa.index') }}" class="btn btn-primary">Registrasi</a>
            <a href="{{ route('admin.kesejahteraan-siswa.index') }}" class="btn btn-primary">Kesejahteraan</a>
            <a href="{{ route('admin.kontak-siswa.index') }}" class="btn btn-primary">Kontak & Alamat</a>
        </div>
    @endif

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

    @if($isAdmin)
        <div class="d-flex justify-content-start mb-3" style="gap:0.5rem;">
            <input type="text" id="search" class="form-control form-control-sm"
                placeholder="Cari Nama Siswa..." style="max-width: 220px;">

            <select id="rombelFilter" class="form-control form-control-sm" style="max-width:200px;">
                <option value="">Semua Rombel</option>
                @foreach($rombels as $rombel)
                    <option value="{{ $rombel->id }}">{{ $rombel->nama_rombel }}</option>
                @endforeach
            </select>
        </div>
    @endif

    @if($isSiswa)
        <div class="d-flex justify-content-start mb-3">
            <a href="{{ route($prefix.'periodik.edit', $periodik->first()->id ?? 0) }}"
               class="btn btn-primary px-4"
               style="background: linear-gradient(180deg, #0770d3, #007efd, #55a6f8); color:white;">
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
            }, 2000);
        </script>
    @endif

    <div class="table-responsive rounded-3 overflow-hidden mt-3">
        <table class="table table-bordered" id="periodikTable">
            <thead class="text-white">
                <tr>
                    @if($isAdmin)
                        <th style="width:50px;">No</th>
                        <th>Nama Siswa</th>
                        <th>Tinggi Badan (cm)</th>
                        <th>Berat Badan (kg)</th>
                        <th>Lingkar Kepala (cm)</th>
                        <th>Jarak ke Sekolah (km)</th>
                        <th style="width:80px;">Aksi</th>
                    @else
                        <th>Data Periodik</th>
                        <th>Keterangan</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @foreach($periodik as $index => $p)

                    @if($isAdmin)
                        <tr data-rombel="{{ $p->rombel_id }}">
                            <td>{{ $periodik->firstItem() + $index }}</td>
                            <td class="nama_siswa">{{ $p->nama_lengkap ?? '-' }}</td>
                            <td>{{ $p->tinggi_badan_cm ?? '-' }}</td>
                            <td>{{ $p->berat_badan_kg ?? '-' }}</td>
                            <td>{{ $p->lingkar_kepala_cm ?? '-' }}</td>
                            <td>{{ $p->jarak_sebenarnya_km ? number_format($p->jarak_sebenarnya_km,1) . ' km' : '-' }}</td>

                            <td>
                                <a href="{{ route($prefix.'periodik.edit', $p->siswa_id) }}"
                                   class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" style="width:20px;">
                                </a>

                                @if($p->periodik_id)
                                    <form action="{{ route($prefix.'periodik.destroy', $p->periodik_id) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-no-border"
                                                onclick="return confirm('Yakin ingin menghapus?')">
                                            <img src="{{ asset('images/delete.png') }}" style="width:20px;">
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>

                    @else
                        <tr>
                            <td>Data Fisik</td>
                            <td>
                                <strong>Tinggi:</strong> {{ $p->tinggi_badan_cm ?? '-' }} cm<br>
                                <strong>Berat:</strong> {{ $p->berat_badan_kg ?? '-' }} kg<br>
                                <strong>Lingkar Kepala:</strong> {{ $p->lingkar_kepala_cm ?? '-' }} cm
                            </td>
                        </tr>

                        <tr>
                            <td>Jarak & Waktu Tempuh</td>
                            <td>
                                <strong>Jarak:</strong> {{ $p->jarak_ke_sekolah ?? '-' }}<br>
                                <strong>Jarak Sebenarnya:</strong> {{ $p->jarak_sebenarnya_km ?? '-' }} km<br>
                                <strong>Waktu Tempuh:</strong>
                                {{ $p->waktu_tempuh_jam ? $p->waktu_tempuh_jam.' jam ' : '' }}
                                {{ $p->waktu_tempuh_menit ? $p->waktu_tempuh_menit.' menit' : '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td>Data Keluarga</td>
                            <td><strong>Jumlah Saudara:</strong> {{ $p->jumlah_saudara ?? '-' }}</td>
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

@if($isAdmin)
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

            const matchNama = nama.includes(searchValue);
            const matchRombel = rombelValue === '' || rombel === rombelValue;

            row.style.display = (matchNama && matchRombel) ? '' : 'none';
        });
    }

    searchInput.addEventListener('keyup', filterTable);
    rombelSelect.addEventListener('change', filterTable);
</script>
@endif

@endsection
