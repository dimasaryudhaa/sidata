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

    @if($isSiswa)
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route($prefix.'beasiswa.create') }}" class="btn btn-primary">
                + Tambah Beasiswa
            </a>
        </div>

        <div class="table-responsive rounded-3 overflow-hidden mt-3">
            <table class="table table-bordered">
                <thead class="text-white">
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Jenis Beasiswa</th>
                        <th>Keterangan</th>
                        <th>Tahun Mulai</th>
                        <th>Tahun Akhir</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($beasiswa as $index => $item)
                        <tr>
                            <td>{{ $beasiswa->firstItem() + $index }}</td>
                            <td>{{ $item->jenis_beasiswa ?? '-' }}</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                            <td>{{ $item->tahun_mulai ?? '-' }}</td>
                            <td>{{ $item->tahun_selesai ?? '-' }}</td>
                            <td>
                                <a href="{{ route($prefix.'beasiswa.edit', $item->id) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" style="width:20px;height:20px;">
                                </a>
                                <form action="{{ route($prefix.'beasiswa.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-no-border"
                                        onclick="return confirm('Yakin ingin menghapus beasiswa ini?');">
                                        <img src="{{ asset('images/delete.png') }}" style="width:20px;height:20px;">
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $beasiswa->links('pagination::bootstrap-5') }}
            </div>
        </div>

    @else
        <div class="d-flex justify-content-start align-items-center mb-3" style="gap: 0.5rem;">
            <input type="text" id="search" class="form-control form-control-sm"
                   placeholder="Cari Nama Siswa..." style="max-width: 250px;">

            <select id="rombelFilter" class="form-control form-control-sm" style="max-width: 250px;">
                <option value="">Semua Rombel</option>
                @foreach($rombels as $rombel)
                    <option value="{{ $rombel->id }}">{{ $rombel->nama_rombel }}</option>
                @endforeach
            </select>
        </div>

        <div class="table-responsive rounded-3 overflow-hidden mt-3">
            <table class="table table-bordered" id="beasiswaTable">
                <thead class="text-white">
                    <tr>
                        <th style="width:50px;">No</th>
                        <th style="width:500px;">Nama Siswa</th>
                        <th>Jumlah Beasiswa</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($beasiswa as $index => $item)
                        <tr data-rombel="{{ $item->rombel_id }}">
                            <td>{{ $beasiswa->firstItem() + $index }}</td>
                            <td class="nama_siswa">{{ $item->nama_lengkap ?? '-' }}</td>
                            <td>{{ $item->jumlah_beasiswa ?? 0 }}</td>
                            <td>
                                <a href="{{ route($prefix.'beasiswa.show', $item->siswa_id) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/view.png') }}" style="width:20px;height:20px;">
                                </a>
                                <a href="{{ route($prefix.'beasiswa.create', ['siswa_id' => $item->siswa_id]) }}"
                                   class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/tambah2.png') }}" style="width:20px;height:20px;">
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $beasiswa->links('pagination::bootstrap-5') }}
        </div>

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
                    const matchNama = nama.includes(searchValue);
                    const matchRombel = rombelValue === "" || rombel === rombelValue;

                    row.style.display = (matchNama && matchRombel) ? "" : "none";
                });
            }

            searchInput.addEventListener("keyup", filterTable);
            rombelSelect.addEventListener("change", filterTable);
        </script>
    @endif

</div>

@endsection
