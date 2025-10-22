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

    <div class="d-flex justify-content-start align-items-center mb-3" style="gap: 0.5rem;">
        <input type="text" id="search" class="form-control form-control-sm" placeholder="Cari Nama Siswa" style="max-width: 200px;">

        <select id="rombelFilter" class="form-control form-control-sm" style="max-width: 200px;">
            <option value="">Semua Rombel</option>
            @foreach($rombels as $rombel)
                <option value="{{ $rombel->id }}">{{ $rombel->nama_rombel }}</option>
            @endforeach
        </select>
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
        <table class="table table-bordered" id="kesejahteraanTable">
            <thead class="text-white">
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama Siswa</th>
                    <th>Jumlah Kesejahteraan</th>
                    <th style="width: 180px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kesejahteraan as $index => $k)
                <tr data-rombel="{{ $k->rombel_id }}">
                    <td>{{ $kesejahteraan->firstItem() + $index }}</td>
                    <td class="nama_siswa">{{ $k->nama_lengkap ?? '-' }}</td>
                    <td>{{ $k->jumlah_kesejahteraan }}</td>
                    <td>
                        <a href="{{ route('kesejahteraan-siswa.show', $k->siswa_id) }}" class="btn btn-sm btn-no-border">
                            <img src="{{ asset('images/view.png') }}" alt="Lihat Kesejahteraan" style="width:20px; height:20px;">
                        </a>
                        <a href="{{ route('kesejahteraan-siswa.create', ['siswa_id' => $k->siswa_id]) }}" class="btn btn-sm btn-no-border">
                            <img src="{{ asset('images/tambah2.png') }}" alt="Tambah Kesejahteraan" style="width:20px; height:20px;">
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
        {{ $kesejahteraan->links() }}
    </div>
</div>

<script>

const searchInput = document.getElementById('search');
const rombelSelect = document.getElementById('rombelFilter');
const rows = document.querySelectorAll('#kesejahteraanTable tbody tr');

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
