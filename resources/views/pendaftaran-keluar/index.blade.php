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

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('pendaftaran-keluar.create') }}" class="btn btn-sm btn-no-border">
            <img src="{{ asset('images/tambah.png') }}" alt="Tambah Kontak Siswa" style="width:50px; height:50px; margin-right:5px;">
        </a>

        <form class="d-flex mb-3" style="gap:0.5rem;">
            <input type="text" id="search" class="form-control form-control-sm" placeholder="Cari Nama Siswa..." style="max-width: 250px;">
        </form>
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
            }, 5000);
        </script>
    @endif

    <div class="table-responsive rounded-3 overflow-hidden mt-3">
        <table class="table table-bordered" id="pendaftaranKeluarTable">
            <thead class="text-white">
                <tr>
                    <th style="width:60px;">No</th>
                    <th>Nama Peserta Didik</th>
                    <th>PTK</th>
                    <th>Orang Tua</th>
                    <th>Keluar Karena</th>
                    <th>Tanggal Keluar</th>
                    <th>Alasan</th>
                    <th style="width:180px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pendaftaranKeluar as $index => $item)
                    <tr>
                        <td style="width: 60px">{{ $loop->iteration }}</td>
                        <td class="nama_siswa">{{ $item->siswa->nama_lengkap ?? '-' }}</td>
                        <td>{{ $item->ptk->nama_lengkap ?? '-' }}</td>
                        <td>{{ $item->orangTua->nama_ayah ?? '-' }}</td>
                        <td>{{ $item->keluar_karena ?? '-' }}</td>
                        <td>{{ $item->tanggal_keluar ?? '-' }}</td>
                        <td>{{ $item->alasan ?? '-' }}</td>
                        <td class="text-center">
                            <form action="{{ route('pendaftaran-keluar.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/delete.png') }}" alt="Hapus" style="width:25px; height:25px;">
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    document.getElementById('search').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#pendaftaranKeluarTable tbody tr');

        rows.forEach(row => {
            let nama = row.querySelector('.nama_siswa').textContent.toLowerCase();
            row.style.display = nama.includes(filter) ? '' : 'none';
        });
    });
</script>
@endsection
