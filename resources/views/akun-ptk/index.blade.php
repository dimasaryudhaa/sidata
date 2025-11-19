@extends('layouts.app')

@section('content')

@php
    $isAdmin = Auth::user()->role === 'admin';
    $isPtk = Auth::user()->role === 'ptk';
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

    <div class="d-flex justify-content-start mb-3" style="gap:0.5rem;">
        @if(!$isPtk)
            <input type="text" id="search" class="form-control form-control-sm" placeholder="Cari Nama PTK..." style="max-width: 250px;">
        @endif
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
        <table class="table table-bordered" id="akunPtkTable">
            <thead class="text-white">
                <tr>
                    <th style="width: 50px;">No</th>
                    @if(!$isPtk)
                        <th>Nama PTK</th>
                    @endif
                    <th>Email</th>
                    <th style="width: 80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>

                        @if(!$isPtk)
                            <td class="nama_ptk">{{ $item->nama_lengkap ?? '-' }}</td>
                        @endif

                        <td>{{ $item->email ?? '-' }}</td>
                        <td>
                            @if($isAdmin || ($isPtk && $ptkId == $item->ptk_id))
                                <a href="{{ $isAdmin
                                    ? route('admin.akun-ptk.edit', $item->ptk_id)
                                    : route('ptk.akun-ptk.edit', $item->ptk_id) }}"
                                    class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" alt="Edit Akun" style="width:20px; height:20px;">
                                </a>
                            @endif
                            @if($isAdmin)
                                @if($item->akun_id)
                                    <form action="{{ route('admin.akun-ptk.destroy', $item->akun_id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-no-border"
                                            onclick="return confirm('Yakin ingin menghapus akun PTK ini?')">
                                            <img src="{{ asset('images/delete.png') }}" alt="Hapus" style="width:20px; height:20px;">
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-no-border" disabled>
                                        <img src="{{ asset('images/delete.png') }}" alt="Nonaktif" style="width:20px; height:20px; opacity:0.5;">
                                    </button>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $data->links('pagination::bootstrap-5') }}
    </div>
</div>

@if(!$isPtk)
<script>
document.getElementById('search').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#akunPtkTable tbody tr');

    rows.forEach(row => {
        let nama = row.querySelector('.nama_ptk').textContent.toLowerCase();
        row.style.display = nama.includes(filter) ? '' : 'none';
    });
});
</script>
@endif

@endsection
