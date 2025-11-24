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

    .btn-no-border:hover,
    .btn-no-border:focus,
    .btn-no-border:active {
        background: transparent !important;
        box-shadow: none !important;
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

        <div class="d-flex justify-content-between align-items-center mb-3">

            <a href="{{ route('admin.siswa.create') }}" class="btn btn-sm btn-no-border">
                <img src="{{ asset('images/tambah.png') }}" style="width:50px;height:50px;">
            </a>

            <div class="d-flex align-items-center" style="gap:0.5rem;">
                <input type="text" id="search" class="form-control form-control-sm"
                    placeholder="Cari Nama Siswa..." style="max-width:200px;">

                <select id="rombelFilter" class="form-control form-control-sm" style="max-width:200px;">
                    <option value="">Semua Rombel</option>
                    @foreach($rombels as $rombel)
                        <option value="{{ $rombel->id }}">{{ $rombel->nama_rombel }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="table-responsive rounded-3 overflow-hidden mt-3">
            <table class="table table-bordered" id="siswaTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Jenis Kelamin</th>
                        <th>NIS</th>
                        <th>NISN</th>
                        <th>Rayon</th>
                        <th>Rombel</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($siswa as $index => $s)
                        <tr data-rombel="{{ $s->rombel_id }}">
                            <td>{{ $siswa->firstItem() + $index }}</td>
                            <td class="nama_siswa">{{ $s->nama_lengkap ?? '-' }}</td>
                            <td>{{ $s->jenis_kelamin ?? '-' }}</td>
                            <td>{{ $s->nis ?? '-' }}</td>
                            <td>{{ $s->nisn ?? '-' }}</td>
                            <td>{{ $s->nama_rayon ?? '-' }}</td>
                            <td>{{ $s->nama_rombel ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.siswa.edit', $s->id) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" style="width:20px;height:20px;">
                                </a>
                                <form action="{{ route('admin.siswa.destroy', $s->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-no-border"
                                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <img src="{{ asset('images/delete.png') }}" style="width:20px;height:20px;">
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <div class="mt-3">
            {{ $siswa->links('pagination::bootstrap-5') }}
        </div>

    @else

        @php $detail = $siswa->first(); @endphp

        <div class="d-flex mb-3">
            <a href="{{ route('siswa.siswa.edit', $detail->id) }}"
                class="btn btn-primary px-4"
                style="background: linear-gradient(180deg,#0770d3,#007efd,#55a6f8); border-radius:6px;">
                <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
        </div>

        <div class="table-responsive rounded-3 overflow-hidden">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Data Siswa</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Identitas Diri</td>
                        <td>
                            <strong>Nama:</strong> {{ $detail->nama_lengkap ?? '-' }}<br>
                            <strong>Jenis Kelamin:</strong> {{ $detail->jenis_kelamin ?? '-' }}<br>
                            <strong>NIS:</strong> {{ $detail->nis ?? '-' }}<br>
                            <strong>NISN:</strong> {{ $detail->nisn ?? '-' }}
                        </td>
                    </tr>

                    <tr>
                        <td>Tempat & Tanggal Lahir</td>
                        <td>
                            <strong>Tempat Lahir:</strong> {{ $detail->tempat_lahir ?? '-' }}<br>
                            <strong>Tanggal Lahir:</strong> {{ $detail->tanggal_lahir ?? '-' }}
                        </td>
                    </tr>

                    <tr>
                        <td>Data Sekolah</td>
                        <td>
                            <strong>Rayon:</strong> {{ $detail->nama_rayon ?? '-' }}<br>
                            <strong>Rombel:</strong> {{ $detail->nama_rombel ?? '-' }}<br>
                            <strong>Agama:</strong> {{ $detail->agama ?? '-' }}
                        </td>
                    </tr>

                    <tr>
                        <td>Kewarganegaraan</td>
                        <td>
                            <strong>Kewarganegaraan:</strong> {{ $detail->kewarganegaraan ?? '-' }}<br>
                            <strong>Negara Asal:</strong> {{ $detail->negara_asal ?? '-' }}
                        </td>
                    </tr>

                    <tr>
                        <td>Berkebutuhan Khusus</td>
                        <td>{{ $detail->berkebutuhan_khusus ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    @endif

</div>

@if(!$isSiswa)
<script>
    const searchInput = document.getElementById('search');
    const rombelSelect = document.getElementById('rombelFilter');
    const rows = document.querySelectorAll('#siswaTable tbody tr');

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const rombelValue = rombelSelect.value;

        rows.forEach(row => {
            let nama = row.querySelector('.nama_siswa').textContent.toLowerCase();
            let rombel = row.getAttribute('data-rombel');

            row.style.display =
                (nama.includes(searchValue) &&
                (rombelValue === '' || rombel === rombelValue))
                ? '' : 'none';
        });
    }

    searchInput.addEventListener('keyup', filterTable);
    rombelSelect.addEventListener('change', filterTable);
</script>
@endif

@endsection
