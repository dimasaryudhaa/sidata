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
    {{-- Alert sukses --}}
    @if(session('success'))
        <div id="successAlert" class="position-fixed top-50 start-50 translate-middle bg-white text-center p-4 rounded shadow-lg border"
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

    {{-- Tampilan Admin --}}
    @if(!$isPtk)
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form class="d-flex mb-3" style="gap:0.5rem;">
                <input type="text" id="search" class="form-control form-control-sm"
                    placeholder="Cari Nama PTK..." style="max-width: 250px;">
            </form>
        </div>

        <div class="table-responsive rounded-3 overflow-hidden mt-3">
            <table class="table table-bordered" id="kepegawaianPtkTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama PTK</th>
                        <th>Status Kepegawaian</th>
                        <th>NIP</th>
                        <th>Jenis PTK</th>
                        <th>Pangkat/Golongan</th>
                        <th>Sumber Gaji</th>
                        <th style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                        <tr>
                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                            <td class="nama_lengkap">{{ $item->nama_lengkap ?? '-' }}</td>
                            <td>{{ $item->status_kepegawaian ?? '-' }}</td>
                            <td>{{ $item->nip ?? '-' }}</td>
                            <td>{{ $item->jenis_ptk ?? '-' }}</td>
                            <td>{{ $item->pangkat_golongan ?? '-' }}</td>
                            <td>{{ $item->sumber_gaji ?? '-' }}</td>
                            <td>
                                <a href="{{ route('kepegawaian-ptk.edit', ['kepegawaian_ptk' => $item->ptk_id]) }}"
                                    class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px; height:20px;">
                                </a>

                                @if($item->kepegawaian_id ?? false)
                                    <form action="{{ route('kepegawaian-ptk.destroy', $item->kepegawaian_id) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-no-border"
                                            onclick="return confirm('Yakin ingin menghapus data kepegawaian PTK ini?')">
                                            <img src="{{ asset('images/delete.png') }}" alt="Hapus"
                                                 style="width:20px; height:20px;">
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-no-border" disabled>
                                        <img src="{{ asset('images/delete.png') }}" alt="Hapus Nonaktif"
                                             style="width:20px; height:20px; opacity:0.5;">
                                    </button>
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

        <script>
            document.getElementById('search').addEventListener('keyup', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#kepegawaianPtkTable tbody tr');
                rows.forEach(row => {
                    let nama = row.querySelector('.nama_lengkap').textContent.toLowerCase();
                    row.style.display = nama.includes(filter) ? '' : 'none';
                });
            });
        </script>

    @else
        @php $dataPtk = $data->first(); @endphp

        @if($dataPtk && $dataPtk->kepegawaian_id)
            <div class="d-flex justify-content-start align-items-center mb-3">
                <a href="{{ route('kepegawaian-ptk.edit', ['kepegawaian_ptk' => $dataPtk->kepegawaian_id]) }}"
                    class="btn btn-primary px-4"
                    style="background: linear-gradient(180deg, #0770d3, #007efd, #55a6f8); color: white; border-radius: 6px;">
                    <i class="bi bi-pencil-square me-2"></i> Edit
                </a>
            </div>
        @endif

        <div class="table-responsive rounded-3 overflow-hidden mt-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Data Kepegawaian</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Status Kepegawaian</td>
                        <td>{{ $dataPtk->status_kepegawaian ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>NIP</td>
                        <td>{{ $dataPtk->nip ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>NIY/NIGK</td>
                        <td>{{ $dataPtk->niy_nigk ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>NUPTK</td>
                        <td>{{ $dataPtk->nuptk ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Jenis PTK</td>
                        <td>{{ $dataPtk->jenis_ptk ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>SK Pengangkatan</td>
                        <td>{{ $dataPtk->sk_pengangkatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>TMT Pengangkatan</td>
                        <td>{{ $dataPtk->tmt_pengangkatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Lembaga Pengangkat</td>
                        <td>{{ $dataPtk->lembaga_pengangkat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>SK CPNS</td>
                        <td>{{ $dataPtk->sk_cpns ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>TMT PNS</td>
                        <td>{{ $dataPtk->tmt_pns ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Pangkat/Golongan</td>
                        <td>{{ $dataPtk->pangkat_golongan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Sumber Gaji</td>
                        <td>{{ $dataPtk->sumber_gaji ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Kartu Pegawai</td>
                        <td>{{ $dataPtk->kartu_pegawai ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Kartu Keluarga</td>
                        <td>{{ $dataPtk->kartu_keluarga ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>

        </div>
    @endif
</div>
@endsection
