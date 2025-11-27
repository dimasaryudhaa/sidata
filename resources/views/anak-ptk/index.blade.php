@extends('layouts.app')

@section('content')

@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    $isPtk = $user->role === 'ptk';
    $prefix = $isAdmin ? 'admin.' : 'ptk.';
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

    @if(auth()->user()->role === 'admin')
        <div class="mb-3 d-flex flex-wrap gap-2">
            <a href="{{ route('admin.ptk.index') }}" class="btn btn-primary">Ptk</a>
            <a href="{{ route('admin.akun-ptk.index') }}" class="btn btn-primary">Akun</a>
            <a href="{{ route('admin.kontak-ptk.index') }}" class="btn btn-primary">Kontak</a>
            <a href="{{ route('admin.dokumen-ptk.index') }}" class="btn btn-primary">Dokumen</a>
            <a href="{{ route('admin.anak-ptk.index') }}" class="btn btn-primary">Anak</a>
            <a href="{{ route('admin.keluarga-ptk.index') }}" class="btn btn-primary">Keluarga</a>
            <a href="{{ route('admin.tunjangan.index') }}" class="btn btn-primary">Tunjangan</a>
            <a href="{{ route('admin.kesejahteraan-ptk.index') }}" class="btn btn-primary">Kesejahteraan</a>
        </div>
    @endif

    @if(auth()->user()->role === 'ptk')
        <div class="mb-3 d-flex flex-wrap gap-2">
            <a href="{{ route('ptk.ptk.index') }}" class="btn btn-primary">Ptk</a>
            <a href="{{ route('ptk.akun-ptk.index') }}" class="btn btn-primary">Akun</a>
            <a href="{{ route('ptk.kontak-ptk.index') }}" class="btn btn-primary">Kontak</a>
            <a href="{{ route('ptk.dokumen-ptk.index') }}" class="btn btn-primary">Dokumen</a>
            <a href="{{ route('ptk.anak-ptk.index') }}" class="btn btn-primary">Anak</a>
            <a href="{{ route('ptk.keluarga-ptk.index') }}" class="btn btn-primary">Keluarga</a>
            <a href="{{ route('ptk.tunjangan.index') }}" class="btn btn-primary">Tunjangan</a>
            <a href="{{ route('ptk.kesejahteraan-ptk.index') }}" class="btn btn-primary">Kesejahteraan</a>
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

    @if($isPtk)
        <div class="table-responsive rounded-3 mt-3">
            <table class="table table-bordered">
                <thead class="text-white">
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Nama Anak</th>
                        <th>Status Anak</th>
                        <th>Jenjang</th>
                        <th>NISN</th>
                        <th>Jenis Kelamin</th>
                        <th>Tempat, Tanggal Lahir</th>
                        <th>Tahun Masuk</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($anakPtk as $index => $anak)
                    <tr>
                        <td>{{ $anakPtk->firstItem() + $index }}</td>
                        <td>{{ $anak->nama_anak ?? '-' }}</td>
                        <td>{{ $anak->status_anak ?? '-' }}</td>
                        <td>{{ $anak->jenjang ?? '-' }}</td>
                        <td>{{ $anak->nisn ?? '-' }}</td>
                        <td>{{ $anak->jenis_kelamin ?? '-' }}</td>
                        <td>{{ $anak->tempat_lahir ?? '-' }}, {{ $anak->tanggal_lahir ?? '-' }}</td>
                        <td>{{ $anak->tahun_masuk ?? '-' }}</td>
                        <td>
                            @if($anak->anak_id)
                                <a href="{{ route($prefix.'anak-ptk.edit', ['anak_ptk' => $anak->anak_id]) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" alt="Edit Anak" style="width:20px; height:20px;">
                                </a>
                            @endif

                            @if($isAdmin && $anak->anak_id)
                                <form action="{{ route($prefix.'anak-ptk.destroy', $anak->anak_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-no-border"
                                        onclick="return confirm('Yakin ingin menghapus anak ini?')">
                                        <img src="{{ asset('images/delete.png') }}" alt="Hapus Anak" style="width:20px; height:20px;">
                                    </button>
                                </form>
                            @elseif(!$anak->anak_id)
                                <button class="btn btn-sm btn-no-border" disabled>
                                    <img src="{{ asset('images/delete.png') }}" alt="Nonaktif" style="width:20px; height:20px; opacity:0.5;">
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">Belum ada data anak.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $anakPtk->links('pagination::bootstrap-5') }}
            </div>
        </div>

    @else
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form class="d-flex mb-3" style="gap:0.5rem;">
                <input type="text" id="search" class="form-control form-control-sm" placeholder="Cari Nama PTK..." style="max-width: 250px;">
            </form>
        </div>

        <div class="table-responsive rounded-3 overflow-hidden mt-3">
            <table class="table table-bordered" id="anakPtkTable">
                <thead class="text-white">
                    <tr>
                        <th style="width:50px;">No</th>
                        <th style="width: 500px">Nama PTK</th>
                        <th>Jumlah Anak</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($anakPtk as $index => $anak)
                    <tr>
                        <td>{{ $anakPtk->firstItem() + $index }}</td>
                        <td class="nama_ptk">{{ $anak->nama_lengkap ?? '-' }}</td>
                        <td>{{ $anak->jumlah_anak ?? 0 }}</td>
                        <td>
                            <a href="{{ route($prefix.'anak-ptk.show', $anak->ptk_id) }}" class="btn btn-sm btn-no-border">
                                <img src="{{ asset('images/view.png') }}" alt="Lihat Anak" style="width:20px; height:20px;">
                            </a>
                            <a href="{{ route($prefix.'anak-ptk.create', ['ptk_id' => $anak->ptk_id]) }}" class="btn btn-sm btn-no-border">
                                <img src="{{ asset('images/tambah2.png') }}" alt="Tambah Anak" style="width:20px; height:20px;">
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $anakPtk->links('pagination::bootstrap-5') }}
        </div>

        <script>
        document.getElementById('search')?.addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#anakPtkTable tbody tr');
            rows.forEach(row => {
                let nama = row.querySelector('.nama_ptk').textContent.toLowerCase();
                row.style.display = nama.includes(filter) ? '' : 'none';
            });
        });
        </script>
    @endif
</div>

@endsection
