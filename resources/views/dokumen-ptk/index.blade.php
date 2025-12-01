@extends('layouts.app')

@section('content')

@php
    use Illuminate\Support\Facades\Auth;

    $prefix = auth()->user()->role === 'admin' ? 'admin' : 'ptk';
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

    @if(isset($isPtk) && $isPtk && isset($ptk))
        <div class="mb-3">
            @if($dokumenPtk)
                <a href="{{ route($prefix . '.dokumen-ptk.edit', $ptk->id) }}"
                class="btn btn-primary px-4"
                style="background: linear-gradient(180deg, #0770d3, #007efd, #55a6f8); color: white; border-radius: 6px;">
                    <i class="bi bi-pencil-square me-2"></i> Edit
                </a>
            @else
                <a href="{{ route($prefix . '.dokumen-ptk.create', ['ptk_id' => $ptk->id]) }}"
                class="btn btn-success px-4">
                    <i class="bi bi-upload me-2"></i> Upload
                </a>
            @endif
        </div>

        <div class="table-responsive rounded-3 overflow-hidden mt-3">
            <table class="table table-bordered mt-3">
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
                            'ktp' => 'KTP',
                            'ijazah_sd' => 'Ijazah SD',
                            'ijazah_smp' => 'Ijazah SMP',
                            'ijazah_sma' => 'Ijazah SMA',
                            'ijazah_s1' => 'Ijazah S1',
                            'ijazah_s2' => 'Ijazah S2',
                            'ijazah_s3' => 'Ijazah S3',
                        ];
                        $no = 1;
                    @endphp

                    @foreach($dokumenList as $field => $nama)
                        @php $file = $dokumenPtk->$field ?? null; @endphp
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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form class="d-flex mb-3" style="gap:0.5rem;">
                <input type="text" id="search" class="form-control form-control-sm"
                       placeholder="Cari Nama PTK..." style="max-width:250px;">
            </form>
        </div>

        <div class="table-responsive rounded-3 overflow-auto mt-3">
            <table class="table table-bordered" id="dokumenTable">
                <thead class="text-white">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="width: 500px">Nama PTK</th>
                        <th>Status</th>
                        <th style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dokumen as $index => $d)
                    <tr>
                        <td>{{ $dokumen->firstItem() + $index }}</td>
                        <td class="nama_ptk">{{ $d->nama_lengkap ?? '-' }}</td>

                        <td>
                            @if($d->jumlah_dokumen > 0)
                                <span class="badge bg-success">Sudah Mengumpulkan</span>
                            @else
                                <span class="badge bg-danger">Belum Mengumpulkan</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route($prefix . '.dokumen-ptk.show', $d->ptk_id) }}"
                            class="btn btn-sm btn-no-border"
                            title="Lihat Dokumen">
                                <img src="{{ asset('images/view.png') }}" alt="Lihat Dokumen" style="width:20px; height:20px;">
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

        <script>
        const searchInput = document.getElementById('search');
        const tbody = document.querySelector('#kontakPtkTable tbody');

        searchInput.addEventListener('keyup', function() {
            let query = this.value;

            if (query.length === 0) {
                location.reload();
                return;
            }

            fetch(`/admin/kontak-ptk/search?q=` + query)
                .then(res => res.json())
                .then(data => {
                    tbody.innerHTML = '';

                    data.forEach((item, index) => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${index + 1}</td>
                                <td class="nama_ptk">${item.nama_lengkap ?? '-'}</td>
                                <td>${item.no_hp ?? '-'}</td>
                                <td>${item.email ?? '-'}</td>
                                <td>${item.alamat_jalan ?? '-'}</td>
                                <td>${item.kelurahan ?? '-'}</td>
                                <td>${item.kecamatan ?? '-'}</td>
                                <td>${item.kode_pos ?? '-'}</td>
                                <td>
                                    <a href="/admin/kontak-ptk/${item.kontak_id ?? item.ptk_id}/edit"
                                        class="btn btn-sm btn-no-border">
                                        <img src="/images/edit.png" style="width:20px;">
                                    </a>

                                    ${
                                        item.kontak_id ? `
                                        <form action="/admin/kontak-ptk/${item.kontak_id}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-no-border"
                                                onclick="return confirm('Yakin ingin menghapus kontak PTK ini?')">
                                                <img src="/images/delete.png" style="width:20px;">
                                            </button>
                                        </form>
                                        ` : `
                                        <button class="btn btn-sm btn-no-border" disabled>
                                            <img src="/images/delete.png" style="width:20px; opacity:0.5;">
                                        </button>
                                        `
                                    }
                                </td>
                            </tr>
                        `;
                    });
                });
        });
        </script>
    @endif
</div>

@endsection
