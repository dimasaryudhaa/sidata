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
    $isSiswa = $user->role === 'siswa';
@endphp

<div class="container">
    @if(!$isSiswa)
        <div class="d-flex justify-content-start align-items-center mb-3" style="gap: 0.5rem;">
            <input type="text" id="search" class="form-control form-control-sm" placeholder="Cari Nama Siswa" style="max-width: 200px;">
            <select id="rombelFilter" class="form-control form-control-sm" style="max-width: 200px;">
                <option value="">Semua Rombel</option>
                @foreach($rombels as $rombel)
                    <option value="{{ $rombel->id }}">{{ $rombel->nama_rombel }}</option>
                @endforeach
            </select>
        </div>
    @else
        <div class="d-flex justify-content-start align-items-center mb-3">
            <a href="{{ route('orang-tua.edit', ['orang_tua' => $data[0]->siswa_id ?? Auth::user()->id]) }}"
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
        <table class="table table-bordered" id="orangTuaTable">
            <thead class="text-white" style="background:linear-gradient(180deg,#0770d3,#007efd,#55a6f8);">
                <tr>
                    @if(!$isSiswa)
                        <th style="width:50px;">No</th>
                        <th>Nama Siswa</th>
                        <th>Nama Ayah</th>
                        <th>Nama Ibu</th>
                        <th>Nama Wali</th>
                        <th style="width:80px;">Aksi</th>
                    @else
                        <th style="width:50px;">No</th>
                        <th>Orang Tua</th>
                        <th>Keterangan</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @foreach($data as $index => $item)
                    @if(!$isSiswa)
                        <tr data-rombel="{{ $item->rombel_id ?? '' }}">
                            <td>{{ $data->firstItem() + $index }}</td>
                            <td class="nama_siswa">{{ $item->nama_lengkap ?? '-' }}</td>
                            <td>{{ $item->nama_ayah ?? '-' }}</td>
                            <td>{{ $item->nama_ibu ?? '-' }}</td>
                            <td>{{ $item->nama_wali ?? '-' }}</td>
                            <td>
                                <a href="{{ route('orang-tua.edit', ['orang_tua' => $item->siswa_id]) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px; height:20px;">
                                </a>

                                @if($item->orang_tua_id)
                                    <form action="{{ route('orang-tua.destroy', $item->orang_tua_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-no-border"
                                            onclick="return confirm('Yakin ingin menghapus data orang tua ini?')">
                                            <img src="{{ asset('images/delete.png') }}" alt="Hapus" style="width:20px; height:20px;">
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ $index * 3 + 1 }}</td>
                            <td>Ayah</td>
                            <td>
                                <strong>Nama:</strong> {{ $item->nama_ayah ?? '-' }}<br>
                                <strong>NIK:</strong> {{ $item->nik_ayah ?? '-' }}<br>
                                <strong>Tahun Lahir:</strong> {{ $item->tahun_lahir_ayah ?? '-' }}<br>
                                <strong>Pendidikan:</strong> {{ $item->pendidikan_ayah ?? '-' }}<br>
                                <strong>Pekerjaan:</strong> {{ $item->pekerjaan_ayah ?? '-' }}<br>
                                <strong>Penghasilan:</strong> {{ $item->penghasilan_ayah ?? '-' }}<br>
                                <strong>Kebutuhan Khusus:</strong> {{ $item->kebutuhan_khusus_ayah ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td>{{ $index * 3 + 2 }}</td>
                            <td>Ibu</td>
                            <td>
                                <strong>Nama:</strong> {{ $item->nama_ibu ?? '-' }}<br>
                                <strong>NIK:</strong> {{ $item->nik_ibu ?? '-' }}<br>
                                <strong>Tahun Lahir:</strong> {{ $item->tahun_lahir_ibu ?? '-' }}<br>
                                <strong>Pendidikan:</strong> {{ $item->pendidikan_ibu ?? '-' }}<br>
                                <strong>Pekerjaan:</strong> {{ $item->pekerjaan_ibu ?? '-' }}<br>
                                <strong>Penghasilan:</strong> {{ $item->penghasilan_ibu ?? '-' }}<br>
                                <strong>Kebutuhan Khusus:</strong> {{ $item->kebutuhan_khusus_ibu ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td>{{ $index * 3 + 3 }}</td>
                            <td>Wali</td>
                            <td>
                                <strong>Nama:</strong> {{ $item->nama_wali ?? '-' }}<br>
                                <strong>NIK:</strong> {{ $item->nik_wali ?? '-' }}<br>
                                <strong>Tahun Lahir:</strong> {{ $item->tahun_lahir_wali ?? '-' }}<br>
                                <strong>Pendidikan:</strong> {{ $item->pendidikan_wali ?? '-' }}<br>
                                <strong>Pekerjaan:</strong> {{ $item->pekerjaan_wali ?? '-' }}<br>
                                <strong>Penghasilan:</strong> {{ $item->penghasilan_wali ?? '-' }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@if(!$isSiswa)
<script>
const searchInput = document.getElementById('search');
const rombelSelect = document.getElementById('rombelFilter');
const rows = document.querySelectorAll('#orangTuaTable tbody tr');

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
@endif

@endsection
