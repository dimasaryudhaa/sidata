@extends('layouts.app')

@section('content')

@php
    $prefix = auth()->user()->role === 'admin' ? 'admin' : 'siswa';
    $isAdmin = auth()->user()->role === 'admin';
    $isSiswa = auth()->user()->role === 'siswa';
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
    cursor: pointer;
}

.btn-no-border.disabled {
    opacity: 0.4;
    cursor: not-allowed;
    pointer-events: none;
}

.modal-lg {
    max-width: 800px;
}

.modal-body img {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 0 auto;
}
</style>


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


<div class="container">

    @if(auth()->user()->role === 'siswa')
        <div class="mb-3 d-flex flex-wrap gap-2">
            <a href="{{ route('siswa.siswa.index') }}" class="btn btn-primary">Siswa</a>
            <a href="{{ route('siswa.akun-siswa.index') }}" class="btn btn-primary">Akun</a>
            <a href="{{ route('siswa.dokumen-siswa.index') }}" class="btn btn-primary">Dokumen</a>
            <a href="{{ route('siswa.periodik.index') }}" class="btn btn-primary">Periodik</a>
            <a href="{{ route('siswa.beasiswa.index') }}" class="btn btn-primary">Beasiswa</a>
            <a href="{{ route('siswa.prestasi.index') }}" class="btn btn-primary">Prestasi</a>
            <a href="{{ route('siswa.orang-tua.index') }}" class="btn btn-primary">Orang Tua</a>
            <a href="{{ route('siswa.registrasi-siswa.index') }}" class="btn btn-primary">Registrasi</a>
            <a href="{{ route('siswa.kesejahteraan-siswa.index') }}" class="btn btn-primary">Kesejahteraan</a>
            <a href="{{ route('siswa.kontak-siswa.index') }}" class="btn btn-primary">Kontak & Alamat</a>
        </div>
    @endif

@if($isSiswa && isset($siswa))

    <div class="mb-3">
        @if($dokumenSiswa)
            <a href="{{ route($prefix . '.dokumen-siswa.edit', $siswa->id) }}"
               class="btn btn-primary px-4"
               style="background: linear-gradient(180deg, #0770d3, #007efd, #55a6f8); color:white;">
               <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
        @else
            <a href="{{ route($prefix . '.dokumen-siswa.create', ['peserta_didik_id' => $siswa->id]) }}"
               class="btn btn-success px-4">
               <i class="bi bi-upload me-2"></i> Upload
            </a>
        @endif
    </div>

    <div class="table-responsive rounded-3">
        <table class="table table-bordered ">
            <thead class="text-white">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Dokumen</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $dokumenList = [
                        'akte_kelahiran' => 'Akte Kelahiran',
                        'kartu_keluarga' => 'Kartu Keluarga',
                        'ktp_ayah' => 'KTP Ayah',
                        'ktp_ibu' => 'KTP Ibu',
                        'ijazah_sd' => 'Ijazah SD',
                        'ijazah_smp' => 'Ijazah SMP',
                    ];
                    $no = 1;
                @endphp

                @foreach($dokumenList as $field => $nama)
                    @php $file = $dokumenSiswa->$field ?? null; @endphp

                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $nama }}</td>
                        <td>
                            @if($file)
                                @if(Str::endsWith($file, ['.jpg', '.jpeg', '.png', '.gif']))
                                    <img src="{{ asset('storage/' . $file) }}"
                                         class="img-thumbnail preview-trigger"
                                         data-nama="{{ $nama }}"
                                         data-src="{{ asset('storage/' . $file) }}"
                                         data-type="image"
                                         style="max-width:100px; cursor:pointer;">
                                @else
                                    <a href="#"
                                       class="preview-trigger text-decoration-none"
                                       data-nama="{{ $nama }}"
                                       data-src="{{ asset('storage/' . $file) }}"
                                       data-type="file">
                                       <i class="bi bi-file-earmark-text"></i> {{ basename($file) }}
                                    </a>
                                @endif
                            @else
                                <span class="text-muted">Belum diunggah</span>
                            @endif
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>

@else

<div class="d-flex gap-2 mb-3">
    <input type="text" id="search" class="form-control form-control-sm"
           placeholder="Cari Nama Siswa" style="max-width:200px;">

    <select id="rombelFilter" class="form-control form-control-sm" style="max-width:200px;">
        <option value="">Semua Rombel</option>
        @foreach($rombels as $rombel)
            <option value="{{ $rombel->id }}">{{ $rombel->nama_rombel }}</option>
        @endforeach
    </select>
</div>


<div class="table-responsive rounded-3 overflow-auto mt-3" style="max-height: 550px;">
    <table class="table table-bordered" id="dokumenTable">
        <thead class="text-white">
            <tr>
                <th width="50">No</th>
                <th>Nama Siswa</th>
                <th>Status</th>
                <th width="80">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dokumen as $index => $d)
            <tr data-rombel="{{ $d->rombel_id }}">
                <td>{{ $dokumen->firstItem() + $index }}</td>
                <td class="nama_siswa">{{ $d->nama_lengkap ?? '-' }}</td>

                <td>
                    @if($d->jumlah_dokumen > 0)
                        <span class="badge bg-success">Sudah Mengumpulkan</span>
                    @else
                        <span class="badge bg-danger">Belum Mengumpulkan</span>
                    @endif
                </td>

                <td>
                    <a href="{{ route($prefix . '.dokumen-siswa.show', $d->peserta_didik_id) }}"
                       class="btn btn-sm btn-no-border">
                        <img src="{{ asset('images/view.png') }}" style="width:20px;">
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $dokumen->links('pagination::bootstrap-5') }}
</div>

@endif
</div>

<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:#007efd; color:white;">
                <h5 class="modal-title" id="previewModalLabel">Preview Dokumen</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">
                <h6 id="docName" class="fw-bold text-primary mb-3"></h6>
                <div id="docPreview"></div>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const previewTriggers = document.querySelectorAll('.preview-trigger');
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    const docName = document.getElementById('docName');
    const docPreview = document.getElementById('docPreview');

    previewTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const nama = this.dataset.nama;
            const src = this.dataset.src;
            const type = this.dataset.type;

            docName.textContent = nama;

            if (type === 'image') {
                docPreview.innerHTML = `<img src="${src}" class="img-fluid rounded shadow">`;
            } else {
                docPreview.innerHTML = `
                    <iframe src="${src}" width="100%" height="500" style="border:none;"></iframe>
                    <a href="${src}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="bi bi-box-arrow-up-right"></i> Buka File
                    </a>`;
            }

            modal.show();
        });
    });

    const searchInput = document.getElementById('search');
    const rombelSelect = document.getElementById('rombelFilter');
    const rows = document.querySelectorAll('#dokumenTable tbody tr');

    function filterTable() {
        const s = searchInput.value.toLowerCase();
        const r = rombelSelect.value;

        rows.forEach(row => {
            const nama = row.querySelector('.nama_siswa').textContent.toLowerCase();
            const rombel = row.dataset.rombel;

            const matchNama = nama.includes(s);
            const matchRombel = r === '' || rombel === r;

            row.style.display = matchNama && matchRombel ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('keyup', filterTable);
    if (rombelSelect) rombelSelect.addEventListener('change', filterTable);

});
</script>

@endsection
