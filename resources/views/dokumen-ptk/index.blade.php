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

/* Tambahan: gaya tombol nonaktif */
.btn-disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}
</style>

<div class="container">

    <div class="d-flex justify-content-start align-items-center mb-3" style="gap: 0.5rem;">
        <input type="text" id="search" class="form-control form-control-sm" placeholder="Cari Nama PTK" style="max-width: 200px;">
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
        <table class="table table-bordered" id="dokumenTable">
            <thead class="text-white">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama PTK</th>
                    <th>Status</th>
                    <th style="width: 80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dokumen as $index => $d)
                <tr>
                    <td>{{ $dokumen->firstItem() + $index }}</td>
                    <td class="nama_ptk">{{ $d->nama_lengkap ?? '-' }}</td>

                    <td>
                        @if($d->jumlah_dokumen > 0)
                            <span class="badge bg-success">Sudah Mengumpulkan</span>
                        @else
                            <span class="badge bg-danger">Belum Mengumpulkan</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('dokumen-ptk.show', $d->ptk_id) }}" class="btn btn-sm btn-no-border" title="Lihat Dokumen">
                            <img src="{{ asset('images/view.png') }}" alt="Lihat Dokumen" style="width:20px; height:20px;">
                        </a>

                        @if(Auth::user()->role === 'admin')
                            @if($d->jumlah_dokumen > 0)
                                <button class="btn btn-sm btn-no-border btn-disabled" title="Admin tidak dapat mengedit dokumen">
                                    <img src="{{ asset('images/edit.png') }}" alt="Edit Dokumen" style="width:20px; height:20px;">
                                </button>
                            @else
                                <button class="btn btn-sm btn-no-border btn-disabled" title="Admin tidak dapat menambah dokumen">
                                    <img src="{{ asset('images/tambah2.png') }}" alt="Upload Dokumen" style="width:20px; height:20px;">
                                </button>
                            @endif
                        @else
                            @if($d->jumlah_dokumen > 0)
                                <a href="{{ route('dokumen-ptk.edit', $d->ptk_id) }}" class="btn btn-sm btn-no-border" title="Edit Dokumen">
                                    <img src="{{ asset('images/edit.png') }}" alt="Edit Dokumen" style="width:20px; height:20px;">
                                </a>
                            @else
                                <a href="{{ route('dokumen-ptk.create', ['ptk_id' => $d->ptk_id]) }}" class="btn btn-sm btn-no-border" title="Upload Dokumen">
                                    <img src="{{ asset('images/tambah2.png') }}" alt="Upload Dokumen" style="width:20px; height:20px;">
                                </a>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $dokumen->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
const searchInput = document.getElementById('search');
const rows = document.querySelectorAll('#dokumenTable tbody tr');

function filterTable() {
    const searchValue = searchInput.value.toLowerCase();

    rows.forEach(row => {
        const nama = row.querySelector('.nama_ptk').textContent.toLowerCase();
        row.style.display = nama.includes(searchValue) ? '' : 'none';
    });
}

searchInput.addEventListener('keyup', filterTable);
</script>

@endsection
