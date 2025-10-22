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
        <a href="{{ route('siswa.create') }}" class="btn btn-sm btn-no-border">
            <img src="{{ asset('images/tambah.png') }}" alt="Tambah Siswa" style="width:50px; height:50px; margin-right:5px;">
        </a>

        <div class="d-flex" style="gap: 0.5rem;">
            <input type="text" id="search" class="form-control form-control-sm" placeholder="Cari Nama Siswa..." style="max-width: 250px;">
            <select id="rombelFilter" class="form-control form-control-sm" style="max-width: 200px;">
                <option value="">Semua Rombel</option>
                @foreach($rombels as $rombel)
                    <option value="{{ $rombel->id }}">{{ $rombel->nama_rombel }}</option>
                @endforeach
            </select>
        </div>
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
        <table class="table table-bordered" id="siswaTable">
            <thead class="text-white">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Siswa</th>
                    <th>Jenis Kelamin</th>
                    <th>NIS</th>
                    <th>NISN</th>
                    <th>Rayon</th>
                    <th>Rombel</th>
                    <th style="width: 80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($siswa as $s)
                    <tr data-rombel="{{ $s->rombel_id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td class="nama_siswa">{{ $s->nama_lengkap }}</td>
                        <td>{{ $s->jenis_kelamin }}</td>
                        <td>{{ $s->nis ?? '-' }}</td>
                        <td>{{ $s->nisn ?? '-' }}</td>
                        <td>{{ $s->rayon->nama_rayon ?? '-' }}</td>
                        <td>{{ $s->rombel->nama_rombel ?? '-' }}</td>
                        <td>
                            <a href="{{ route('siswa.edit', $s->id) }}" class="btn btn-sm btn-no-border">
                                <img src="{{ asset('images/edit.png') }}" alt="Edit Siswa" style="width:20px; height:20px; margin-right:5px;">
                            </a>
                            <form action="{{ route('siswa.destroy', $s->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-no-border"
                                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                                    <img src="{{ asset('images/delete.png') }}" alt="Hapus Siswa" style="width:20px; height:20px;">
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
const searchInput = document.getElementById('search');
const rombelSelect = document.getElementById('rombelFilter');
const rows = document.querySelectorAll('#siswaTable tbody tr');

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

@endsection
