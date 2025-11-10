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

    @if(!$isPtk)
        <div class="d-flex justify-content-start align-items-center mb-3" style="gap: 0.5rem;">
            <input type="text" id="search" class="form-control form-control-sm" placeholder="Cari Nama PTK..." style="max-width: 250px;">
        </div>
    @else
        <div class="d-flex justify-start-end align-items-center mb-3">
            <a href="{{ route('kontak-ptk.edit', ['kontak_ptk' => $data[0]->ptk_id ?? Auth::user()->id]) }}"
               class="btn btn-primary px-4"
               style="background: linear-gradient(180deg, #0770d3, #007efd, #55a6f8); color: white; border-radius: 6px;">
               <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
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

    <div class="table-responsive rounded-3 overflow-hidden mt-3">
        <table class="table table-bordered" id="kontakPtkTable">
            <thead class="text-white">
                <tr>
                    <th style="width:50px;">No</th>
                    @if(!$isPtk)
                        <th>Nama PTK</th>
                        <th>No HP</th>
                        <th>Email</th>
                        <th>Alamat Jalan</th>
                        <th>Kelurahan</th>
                        <th>Kecamatan</th>
                        <th>Kode Pos</th>
                        <th style="width:80px;">Aksi</th>
                    @else
                        <th>Data Kontak & Alamat</th>
                        <th>Keterangan</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $item)
                    @if(!$isPtk)
                        <tr>
                            <td>{{ $data->firstItem() + $index }}</td>
                            <td class="nama_ptk">{{ $item->nama_lengkap ?? '-' }}</td>
                            <td>{{ $item->no_hp ?? '-' }}</td>
                            <td>{{ $item->email ?? '-' }}</td>
                            <td>{{ $item->alamat_jalan ?? '-' }}</td>
                            <td>{{ $item->kelurahan ?? '-' }}</td>
                            <td>{{ $item->kecamatan ?? '-' }}</td>
                            <td>{{ $item->kode_pos ?? '-' }}</td>
                            <td>
                                <a href="{{ route('kontak-ptk.edit', ['kontak_ptk' => $item->ptk_id]) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px; height:20px;">
                                </a>
                                @if($item->kontak_id)
                                    <form action="{{ route('kontak-ptk.destroy', $item->kontak_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-no-border"
                                                onclick="return confirm('Yakin ingin menghapus data kontak PTK ini?')">
                                            <img src="{{ asset('images/delete.png') }}" alt="Hapus" style="width:20px; height:20px;">
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-no-border" disabled>
                                        <img src="{{ asset('images/delete.png') }}" alt="Hapus Nonaktif" style="width:20px; height:20px; opacity:0.5;">
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ $index * 2 + 1 }}</td>
                            <td>Informasi Kontak</td>
                            <td>
                                <strong>No HP:</strong> {{ $item->no_hp ?? '-' }}<br>
                                <strong>Email:</strong> {{ $item->email ?? '-' }}<br>
                            </td>
                        </tr>
                        <tr>
                            <td>{{ $index * 2 + 2 }}</td>
                            <td>Alamat Tempat Tinggal</td>
                            <td>
                                <strong>Alamat Jalan:</strong> {{ $item->alamat_jalan ?? '-' }}<br>
                                <strong>RT:</strong> {{ $item->rt ?? '-' }}<br>
                                <strong>RW:</strong> {{ $item->rw ?? '-' }}<br>
                                <strong>Kelurahan:</strong> {{ $item->kelurahan ?? '-' }}<br>
                                <strong>Kecamatan:</strong> {{ $item->kecamatan ?? '-' }}<br>
                                <strong>Kode Pos:</strong> {{ $item->kode_pos ?? '-' }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $data->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
const searchInput = document.getElementById('search');
const rows = document.querySelectorAll('#kontakPtkTable tbody tr');

searchInput.addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();

    rows.forEach(row => {
        let nama = row.querySelector('.nama_ptk')?.textContent.toLowerCase() || '';
        row.style.display = nama.includes(filter) ? '' : 'none';
    });
});
</script>

@endsection
