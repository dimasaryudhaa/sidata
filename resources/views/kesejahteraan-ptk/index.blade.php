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

@php
    $user = Auth::user();
    $isPtk = $user->role === 'ptk';
@endphp

<div class="container">

    {{-- Alert sukses --}}
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
        <div class="table-responsive rounded-3 mt-3">
            <table class="table table-bordered">
                <thead class="text-white">
                    <tr>
                        <th style="width:60px;">No</th>
                        <th>Jenis Kesejahteraan</th>
                        <th>Nama</th>
                        <th>Penyelenggara</th>
                        <th>Dari Tahun</th>
                        <th>Sampai Tahun</th>
                        <th>Status</th>
                        <th style="width:100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kesejahteraan as $index => $item)
                        <tr>
                            <td>{{ $kesejahteraan->firstItem() + $index }}</td>
                            <td>{{ $item->jenis_kesejahteraan ?? '-' }}</td>
                            <td>{{ $item->nama ?? '-' }}</td>
                            <td>{{ $item->penyelenggara ?? '-' }}</td>
                            <td>{{ $item->dari_tahun ?? '-' }}</td>
                            <td>{{ $item->sampai_tahun ?? '-' }}</td>
                            <td>{{ $item->status ?? '-' }}</td>
                            <td>
                                <a href="{{ route('kesejahteraan-ptk.edit', $item->kesejahteraan_id) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px; height:20px;">
                                </a>
                                <form action="{{ route('kesejahteraan-ptk.destroy', $item->kesejahteraan_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-no-border"
                                        onclick="return confirm('Yakin ingin menghapus data kesejahteraan ini?')">
                                        <img src="{{ asset('images/delete.png') }}" alt="Hapus" style="width:20px; height:20px;">
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada data kesejahteraan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $kesejahteraan->links('pagination::bootstrap-5') }}
            </div>
        </div>

    @else
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form class="d-flex mb-3" style="gap:0.5rem;">
                <input type="text" id="search" class="form-control form-control-sm"
                       placeholder="Cari Nama PTK..." style="max-width: 250px;">
            </form>
        </div>

        <div class="table-responsive rounded-3 overflow-hidden mt-3">
            <table class="table table-bordered" id="kesejahteraanTable">
                <thead class="text-white">
                    <tr>
                        <th style="width:50px;">No</th>
                        <th style="width:500px;">Nama PTK</th>
                        <th>Jumlah Kesejahteraan</th>
                        <th style="width:100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kesejahteraan as $index => $item)
                        <tr>
                            <td>{{ $kesejahteraan->firstItem() + $index }}</td>
                            <td class="nama_ptk">{{ $item->nama_lengkap ?? '-' }}</td>
                            <td>{{ $item->jumlah_kesejahteraan ?? 0 }}</td>
                            <td>
                                <a href="{{ route('kesejahteraan-ptk.show', $item->ptk_id) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/view.png') }}" alt="Lihat" style="width:20px; height:20px;">
                                </a>
                                <a href="{{ route('kesejahteraan-ptk.create', ['ptk_id' => $item->ptk_id]) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/tambah2.png') }}" alt="Tambah" style="width:20px; height:20px;">
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $kesejahteraan->links('pagination::bootstrap-5') }}
        </div>

        <script>
            document.getElementById('search')?.addEventListener('keyup', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#kesejahteraanTable tbody tr');
                rows.forEach(row => {
                    let nama = row.querySelector('.nama_ptk').textContent.toLowerCase();
                    row.style.display = nama.includes(filter) ? '' : 'none';
                });
            });
        </script>
    @endif

</div>

@endsection
