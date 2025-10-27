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

<div class="container">

    @if($isSiswa && isset($siswa))
        <div class="mb-3">
            @if($dokumenSiswa)
                <a href="{{ route('dokumen-siswa.edit', $siswa->id) }}" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Edit Dokumen
                </a>
            @else
                <a href="{{ route('dokumen-siswa.create', ['peserta_didik_id' => $siswa->id]) }}" class="btn btn-success">
                    <i class="bi bi-upload"></i> Upload Dokumen
                </a>
            @endif
        </div>

        <div class="table-responsive rounded-3 overflow-auto" style="max-height: 550px;">
            <table class="table table-bordered mt-0">
                <thead class="text-white" style="background: linear-gradient(180deg, #0770d3, #007efd, #55a6f8);">
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
                        @php
                            $file = $dokumenSiswa->$field ?? null;
                        @endphp
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $nama }}</td>
                            <td>
                                @if($file)
                                    @if(Str::endsWith($file, ['.jpg', '.jpeg', '.png', '.gif']))
                                        <img src="{{ asset('storage/' . $file) }}"
                                             alt="{{ $nama }}"
                                             class="img-thumbnail preview-trigger"
                                             data-nama="{{ $nama }}"
                                             data-src="{{ asset('storage/' . $file) }}"
                                             data-type="image"
                                             style="max-width:100px; max-height:100px; cursor:pointer;">
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

        <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background:#007efd; color:white;">
                        <h5 class="modal-title" id="previewModalLabel">Preview Dokumen</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <h6 id="docName" class="mb-3 fw-bold text-primary"></h6>
                        <div id="docPreview"></div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const previewTriggers = document.querySelectorAll('.preview-trigger');
            const modal = new bootstrap.Modal(document.getElementById('previewModal'));
            const docName = document.getElementById('docName');
            const docPreview = document.getElementById('docPreview');

            previewTriggers.forEach(trigger => {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    const nama = this.getAttribute('data-nama');
                    const src = this.getAttribute('data-src');
                    const type = this.getAttribute('data-type');

                    docName.textContent = nama;

                    if (type === 'image') {
                        docPreview.innerHTML = `<img src="${src}" alt="${nama}" class="img-fluid rounded shadow">`;
                    } else {
                        docPreview.innerHTML = `
                            <iframe src="${src}" width="100%" height="500px" style="border:none;"></iframe>
                            <div class="mt-2">
                                <a href="${src}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-box-arrow-up-right"></i> Buka di Tab Baru
                                </a>
                            </div>`;
                    }

                    modal.show();
                });
            });
        });
        </script>

    @else

        <div class="d-flex justify-content-start align-items-center mb-3" style="gap: 0.5rem;">
            <input type="text" id="search" class="form-control form-control-sm" placeholder="Cari Nama Siswa" style="max-width: 200px;">

            <select id="rombelFilter" class="form-control form-control-sm" style="max-width: 200px;">
                <option value="">Semua Rombel</option>
                @foreach($rombels as $rombel)
                    <option value="{{ $rombel->id }}">{{ $rombel->nama_rombel }}</option>
                @endforeach
            </select>
        </div>

        @if(session('success'))
            <div id="successAlert" class="position-fixed top-50 start-50 translate-middle bg-white text-center p-4 rounded shadow-lg border" style="z-index:1050; min-width:320px;">
                <div class="d-flex justify-content-center mb-3">
                    <div class="d-flex justify-content-center align-items-center" style="width:80px; height:80px; background-color:#d4edda; border-radius:50%;">
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

        <div class="table-responsive rounded-3 overflow-auto mt-3" style="max-height: 550px;">
            <table class="table table-bordered" id="dokumenTable">
                <thead class="text-white">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Siswa</th>
                        <th>Status</th>
                        <th style="width: 80px;">Aksi</th>
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
                            <a href="{{ route('dokumen-siswa.show', $d->peserta_didik_id) }}"
                               class="btn btn-sm btn-no-border"
                               title="Lihat Dokumen">
                                <img src="{{ asset('images/view.png') }}" alt="Lihat Dokumen" style="width:20px; height:20px;">
                            </a>

                            @if(auth()->user()->role === 'admin')
                                @if($d->jumlah_dokumen > 0)
                                    <button class="btn btn-sm btn-no-border disabled" title="Edit Dinonaktifkan untuk Admin">
                                        <img src="{{ asset('images/edit.png') }}" alt="Edit" style="width:20px; height:20px; opacity:0.5;">
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-no-border disabled" title="Upload Dinonaktifkan untuk Admin">
                                        <img src="{{ asset('images/tambah2.png') }}" alt="Upload" style="width:20px; height:20px; opacity:0.5;">
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
            {{ $dokumen->links('pagination::bootstrap-5') }}
        </div>

        <script>
        const searchInput = document.getElementById('search');
        const rombelSelect = document.getElementById('rombelFilter');
        const rows = document.querySelectorAll('#dokumenTable tbody tr');

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
</div>

@endsection
