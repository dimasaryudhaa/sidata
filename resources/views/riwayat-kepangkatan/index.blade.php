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
        <form class="d-flex mb-3" style="gap:0.5rem;">
            <input type="text" id="search" class="form-control form-control-sm" placeholder="Cari Nama PTK..." style="max-width: 250px;">
        </form>
    </div>

    @if(session('success'))
        <div id="successAlert" class="position-fixed top-50 start-50 translate-middle bg-white text-center p-4 rounded shadow-lg border" style="z-index:1050; min-width:320px;">
            <div class="d-flex justify-content-center mb-3">
                <div class="d-flex justify-content-center align-items-center" style="width:80px; height:80px; background-color:#d4edda; border-radius:50%;">
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
        <table class="table table-bordered" id="riwayatKepangkatanTable">
            <thead class="text-white">
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Nama PTK</th>
                    <th>Jumlah Riwayat Kepangkatan</th>
                    <th style="width:80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($riwayatKepangkatan as $index => $item)
                <tr>
                    <td>{{ $riwayatKepangkatan->firstItem() + $index }}</td>
                    <td class="nama_ptk">{{ $item->nama_lengkap ?? '-' }}</td>
                    <td>{{ $item->jumlah_riwayat_kepangkatan ?? 0 }}</td>
                    <td>
                        <a href="{{ route('riwayat-kepangkatan.show', $item->ptk_id) }}" class="btn btn-sm btn-no-border">
                            <img src="{{ asset('images/view.png') }}" alt="Lihat Riwayat Kepangkatan" style="width:20px; height:20px;">
                        </a>
                        <a href="{{ route('riwayat-kepangkatan.create', ['ptk_id' => $item->ptk_id]) }}" class="btn btn-sm btn-no-border">
                            <img src="{{ asset('images/tambah2.png') }}" alt="Tambah Riwayat Kepangkatan" style="width:20px; height:20px;">
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $riwayatKepangkatan->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
    document.getElementById('search').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#riwayatKepangkatanTable tbody tr');

        rows.forEach(row => {
            let nama = row.querySelector('.nama_ptk').textContent.toLowerCase();
            row.style.display = nama.includes(filter) ? '' : 'none';
        });
    });
</script>
@endsection
