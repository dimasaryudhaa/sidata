@extends('layouts.app')

@section('content')

@php
    use Illuminate\Support\Facades\Auth;

    $isAdmin = Auth::user()->role === 'admin';
    $isSiswa = Auth::user()->role === 'siswa';
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

    <div class="d-flex justify-content-start mb-3" style="gap:0.5rem;">
        @if(!$isSiswa)
            <input type="text" id="search" class="form-control form-control-sm"
                placeholder="Cari Nama Siswa..." style="max-width: 250px;">
        @endif
    </div>

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
        <table class="table table-bordered" id="akunSiswaTable">
            <thead class="text-white">
                <tr>
                    <th style="width:50px;">No</th>

                    @if(!$isSiswa)
                        <th>Nama Siswa</th>
                    @endif

                    <th>Email</th>
                    <th style="width:80px;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>

                        @if(!$isSiswa)
                            <td class="nama_siswa">{{ $item->nama_lengkap ?? '-' }}</td>
                        @endif

                        <td>{{ $item->email ?? '-' }}</td>

                        <td>
                            <a href="{{ route($prefix.'akun-siswa.edit', $item->peserta_didik_id) }}"
                                class="btn btn-sm btn-no-border">
                                <img src="{{ asset('images/edit.png') }}" style="width:20px; height:20px;">
                            </a>

                            @if($isAdmin)
                                @if($item->akun_id)
                                    <form action="{{ route('admin.akun-siswa.destroy', $item->akun_id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-no-border"
                                            onclick="return confirm('Yakin ingin menghapus akun siswa ini?')">
                                            <img src="{{ asset('images/delete.png') }}" style="width:20px; height:20px;">
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-no-border" disabled>
                                        <img src="{{ asset('images/delete.png') }}" style="width:20px; height:20px; opacity:0.5;">
                                    </button>
                                @endif
                            @endif

                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <div class="mt-3">
        {{ $data->links('pagination::bootstrap-5') }}
    </div>

</div>

@if(!$isSiswa)
<script>
document.getElementById('search').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#akunSiswaTable tbody tr');

    rows.forEach(row => {
        let nama = row.querySelector('.nama_siswa').textContent.toLowerCase();
        row.style.display = nama.includes(filter) ? '' : 'none';
    });
});
</script>
@endif

@endsection
