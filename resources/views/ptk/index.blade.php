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
        @if(!$isPtk)
            <div class="d-flex align-items-center">
                <a href="{{ route('ptk.create') }}" class="btn btn-sm btn-no-border me-2">
                    <img src="{{ asset('images/tambah.png') }}" alt="Tambah PTK"
                        style="width:50px; height:50px;">
                </a>
            </div>

            <div class="d-flex justify-content-start align-items-center mb-3" style="gap: 0.5rem;">
                <input type="text" id="search" class="form-control form-control-sm" placeholder="Cari Nama PTK" style="max-width: 200px;">
            </div>
        @else
            @php
                $dataPtk = $ptk->first();
            @endphp
            @if($dataPtk)
                <div class="d-flex justify-content-start align-items-center mb-3">
                    <a href="{{ route('ptk.edit', ['ptk' => $dataPtk->id ?? Auth::user()->ptk->id]) }}"
                    class="btn btn-primary px-4"
                    style="background: linear-gradient(180deg, #0770d3, #007efd, #55a6f8); color: white; border-radius: 6px;">
                        <i class="bi bi-pencil-square me-2"></i> Edit
                    </a>
                </div>
            @endif
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
        <table class="table table-bordered" id="ptkTable">
            <thead class="text-white" style="background:linear-gradient(180deg,#0770d3,#007efd,#55a6f8);">
                <tr>
                    @if(!$isPtk)
                        <th style="width:50px;">No</th>
                        <th>Nama Lengkap</th>
                        <th>Jenis Kelamin</th>
                        <th>NIK</th>
                        <th>Tempat Lahir</th>
                        <th>Tanggal Lahir</th>
                        <th style="width:80px;">Aksi</th>
                    @else
                        <th style="width:50px;">No</th>
                        <th>Data Diri</th>
                        <th>Keterangan</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @foreach($ptk as $index => $p)
                    @if(!$isPtk)
                        <tr>
                            <td>{{ $ptk->firstItem() + $index }}</td>
                            <td class="nama_ptk">{{ $p->nama_lengkap ?? '-' }}</td>
                            <td>{{ $p->jenis_kelamin ?? '-' }}</td>
                            <td>{{ $p->nik ?? '-' }}</td>
                            <td>{{ $p->tempat_lahir ?? '-' }}</td>
                            <td>{{ $p->tanggal_lahir ?? '-' }}</td>
                            <td>
                                <a href="{{ route('ptk.edit', $p->id) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px; height:20px;">
                                </a>
                                <form action="{{ route('ptk.destroy', $p->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-no-border"
                                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <img src="{{ asset('images/delete.png') }}" alt="Hapus" style="width:20px; height:20px;">
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ $index * 5 + 1 }}</td>
                            <td>Identitas Diri</td>
                            <td>
                                <strong>Nama Lengkap:</strong> {{ $p->nama_lengkap ?? '-' }}<br>
                                <strong>NIK:</strong> {{ $p->nik ?? '-' }}<br>
                                <strong>Jenis Kelamin:</strong> {{ $p->jenis_kelamin ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td>{{ $index * 5 + 2 }}</td>
                            <td>Tempat & Tanggal Lahir</td>
                            <td>
                                <strong>Tempat Lahir:</strong> {{ $p->tempat_lahir ?? '-' }}<br>
                                <strong>Tanggal Lahir:</strong> {{ $p->tanggal_lahir ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td>{{ $index * 5 + 3 }}</td>
                            <td>Data Pribadi</td>
                            <td>
                                <strong>Nama Ibu Kandung:</strong> {{ $p->nama_ibu_kandung ?? '-' }}<br>
                                <strong>Agama:</strong> {{ $p->agama ?? '-' }}<br>
                                <strong>Kode Pos:</strong> {{ $p->kode_pos ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td>{{ $index * 5 + 4 }}</td>
                            <td>Pajak</td>
                            <td>
                                <strong>NPWP:</strong> {{ $p->npwp ?? '-' }}<br>
                                <strong>Nama Wajib Pajak:</strong> {{ $p->nama_wajib_pajak ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td>{{ $index * 5 + 5 }}</td>
                            <td>Kewarganegaraan</td>
                            <td>
                                <strong>Kewarganegaraan:</strong> {{ $p->kewarganegaraan ?? '-' }}<br>
                                <strong>Negara Asal:</strong> {{ $p->negara_asal ?? '-' }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $ptk->links('pagination::bootstrap-5') }}
    </div>

</div>

@if(!$isPtk)
<script>
    const searchInput = document.getElementById('search');
    const rows = document.querySelectorAll('#ptkTable tbody tr');

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();

        rows.forEach(row => {
            const nama = row.querySelector('.nama_ptk')?.textContent.toLowerCase() || '';
            row.style.display = nama.includes(searchValue) ? '' : 'none';
        });
    }

    searchInput.addEventListener('keyup', filterTable);
</script>
@endif

@endsection
