@extends('layouts.app')

@section('content')

@php
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

    @if(auth()->user()->role === 'ptk')
        <div class="mb-3 d-flex flex-wrap gap-2">
            <a href="{{ route('ptk.diklat.index') }}" class="btn btn-primary">Diklat</a>
            <a href="{{ route('ptk.nilai-test.index') }}" class="btn btn-primary">Nilai Test</a>
            <a href="{{ route('ptk.pendidikan-ptk.index') }}" class="btn btn-primary">Pendidikan</a>
            <a href="{{ route('ptk.sertifikat-ptk.index') }}" class="btn btn-primary">Sertifikat</a>
            <a href="{{ route('ptk.beasiswa-ptk.index') }}" class="btn btn-primary">Beasiswa</a>
            <a href="{{ route('ptk.penghargaan.index') }}" class="btn btn-primary">Penghargaan</a>
            <a href="{{ route('ptk.kompetensi-ptk.index') }}" class="btn btn-primary">Kompetensi</a>
            <a href="{{ route('ptk.kompetensi-khusus-ptk.index') }}" class="btn btn-primary">Kompetensi Khusus</a>
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
            <table class="table table-bordered" id="nilaiTestTable">
                <thead class="text-white">
                    <tr>
                        <th style="width:60px;">No</th>
                        <th>Jenis Test</th>
                        <th>Nama Test</th>
                        <th>Penyelenggara</th>
                        <th>Tahun</th>
                        <th>Skor</th>
                        <th>Nomor Peserta</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nilaiTest as $index => $item)
                        <tr>
                            <td>{{ $nilaiTest->firstItem() + $index }}</td>
                            <td>{{ $item->jenis_test ?? '-' }}</td>
                            <td>{{ $item->nama_test ?? '-' }}</td>
                            <td>{{ $item->penyelenggara ?? '-' }}</td>
                            <td>{{ $item->tahun ?? '-' }}</td>
                            <td>{{ $item->skor ?? '-' }}</td>
                            <td>{{ $item->nomor_peserta ?? '-' }}</td>
                            <td>
                                <a href="{{ route($prefix.'nilai-test.edit', ['nilai_test' => $item->nilai_test_id]) }}"
                                   class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px; height:20px;">
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $nilaiTest->links('pagination::bootstrap-5') }}
        </div>

    @else
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form class="d-flex mb-3" style="gap:0.5rem;">
                <input type="text" id="search" class="form-control form-control-sm"
                       placeholder="Cari Nama PTK..." style="max-width:250px;">
            </form>
        </div>

        <div class="table-responsive rounded-3 overflow-hidden mt-3">
            <table class="table table-bordered" id="nilaiTestTable">
                <thead class="text-white">
                    <tr>
                        <th style="width:50px;">No</th>
                        <th style="width:500px;">Nama PTK</th>
                        <th>Jumlah Test</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nilaiTest as $index => $item)
                        <tr>
                            <td>{{ $nilaiTest->firstItem() + $index }}</td>
                            <td class="nama_ptk">{{ $item->nama_lengkap ?? '-' }}</td>
                            <td>{{ $item->jumlah_nilai_test ?? 0 }}</td>
                            <td>
                                <a href="{{ route($prefix.'nilai-test.show', $item->ptk_id) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/view.png') }}" alt="Lihat Nilai Test" style="width:20px; height:20px;">
                                </a>
                                <a href="{{ route($prefix.'nilai-test.create', ['ptk_id' => $item->ptk_id]) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/tambah2.png') }}" alt="Tambah Nilai Test" style="width:20px; height:20px;">
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $nilaiTest->links('pagination::bootstrap-5') }}
        </div>

        <script>
            document.getElementById('search')?.addEventListener('keyup', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#nilaiTestTable tbody tr');
                rows.forEach(row => {
                    let nama = row.querySelector('.nama_ptk').textContent.toLowerCase();
                    row.style.display = nama.includes(filter) ? '' : 'none';
                });
            });
        </script>
    @endif

</div>

@endsection
