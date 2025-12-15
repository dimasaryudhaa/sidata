@extends('layouts.app')

@section('content')

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
            <a href="{{ route('admin.jurusan.index') }}" class="btn btn-primary">Jurusan</a>
            <a href="{{ route('admin.rayon.index') }}" class="btn btn-primary">Rayon</a>
            <a href="{{ route('admin.rombel.index') }}" class="btn btn-primary">Rombel</a>
            <a href="{{ route('admin.semester.index') }}" class="btn btn-primary">Semester</a>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.semester.create') }}" class="btn btn-sm btn-no-border">
            <img src="{{ asset('images/tambah.png') }}" alt="Tambah Jurusan" style="width:50px; height:50px; margin-right:5px;">
        </a>

        <form class="d-flex mb-3" style="gap:0.5rem;">
            <input type="text" id="search" class="form-control form-control-sm" placeholder="Cari Nama Jurusan" style="max-width: 250px;">
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
            }, 2000);
        </script>
    @endif

    <div class="table-responsive rounded-3 overflow-hidden mt-3">
        <table class="table table-bordered" id="semesterTable">
            <thead class="text-white">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Semester</th>
                    <th>Tahun Ajaran</th>
                    <th style="width: 80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($semester as $index => $s)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="nama_semester">{{ $s->nama_semester }}</td>
                        <td>{{ $s->tahun_ajaran }}</td>
                        <td class="">
                            <a href="{{ route('admin.semester.edit', $s->id) }}" class="btn btn-sm btn-no-border">
                                <img src="{{ asset('images/edit.png') }}" alt="Edit Semester" style="width:20px; height:20px; margin-right:5px;">
                            </a>
                            <form action="{{ route('admin.semester.destroy', $s->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-no-border"
                                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                                    <img src="{{ asset('images/delete.png') }}" alt="Hapus Jurusan" style="width:20px; height:20px;">
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $semester->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
document.getElementById('search').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#semesterTable tbody tr');

    rows.forEach(row => {
        let nama = row.querySelector('.nama_semester').textContent.toLowerCase();
        row.style.display = nama.includes(filter) ? '' : 'none';
    });
});
</script>

@endsection
