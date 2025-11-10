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
                    <th style="width:60px;">No</th>
                    @if($isPtk)
                        <th>Jenis Kesejahteraan</th>
                        <th>Nama</th>
                        <th>Penyelenggara</th>
                        <th>Dari Tahun</th>
                        <th>Sampai Tahun</th>
                        <th>Status</th>
                        <th style="width:80px;">Aksi</th>
                    @else
                        <th>Nama PTK</th>
                        <th>Jumlah Kesejahteraan</th>
                        <th style="width:100px;">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($kesejahteraan as $index => $k)
                    <tr>
                        <td>{{ $kesejahteraan->firstItem() + $index }}</td>

                        @if($isPtk)
                            <td>{{ $k->jenis_kesejahteraan ?? '-' }}</td>
                            <td>{{ $k->nama ?? '-' }}</td>
                            <td>{{ $k->penyelenggara ?? '-' }}</td>
                            <td>{{ $k->dari_tahun ?? '-' }}</td>
                            <td>{{ $k->sampai_tahun ?? '-' }}</td>
                            <td>{{ $k->status ?? '-' }}</td>
                            <td>
                                <a href="{{ route('kesejahteraan-ptk.edit', ['kesejahteraan_ptk' => $k->kesejahteraan_id]) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px; height:20px;">
                                </a>
                            </td>
                        @else
                            <td class="nama_ptk">{{ $k->nama_lengkap ?? '-' }}</td>
                            <td>{{ $k->jumlah_kesejahteraan ?? 0 }}</td>
                            <td>
                                <a href="{{ route('kesejahteraan-ptk.show', $k->ptk_id) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/view.png') }}" alt="Lihat" style="width:20px; height:20px;">
                                </a>
                                <a href="{{ route('kesejahteraan-ptk.create', ['ptk_id' => $k->ptk_id]) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/tambah2.png') }}" alt="Tambah" style="width:20px; height:20px;">
                                </a>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $kesejahteraan->links('pagination::bootstrap-5') }}
    </div>

</div>

@if(!$isPtk)
<script>
    const searchInput = document.getElementById('search');
    const rows = document.querySelectorAll('#kesejahteraanTable tbody tr');

    searchInput.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();

        rows.forEach(row => {
            const nama = row.querySelector('.nama_ptk').textContent.toLowerCase();
            row.style.display = nama.includes(filter) ? '' : 'none';
        });
    });
</script>
@endif

@endsection
