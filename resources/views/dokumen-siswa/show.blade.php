@extends('layouts.app')

@section('content')

@php
    use Illuminate\Support\Str;

    $role = auth()->user()->role;

    if ($role === 'admin') {
        $prefix = 'admin';
    } elseif ($role === 'ptk') {
        $prefix = 'ptk';
    } else {
        $prefix = 'siswa';
    }
@endphp

<div class="container">
    <h4>{{ $siswa->nama_lengkap }}</h4>

    <div class="table-responsive rounded-3 overflow-auto mt-3" style="max-height: 550px;">
        <table class="table table-bordered">
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
                        $file = $dokumen->$field ?? null;
                        $ext  = $file ? strtolower(pathinfo($file, PATHINFO_EXTENSION)) : null;
                    @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $nama }}</td>
                        <td>
                            @if($file)
                                @if(in_array($ext, ['jpg','jpeg','png','gif']))
                                    <img src="{{ asset('storage/' . $file) }}"
                                         class="img-thumbnail preview-trigger"
                                         style="max-width:100px; cursor:pointer;"
                                         data-nama="{{ $nama }}"
                                         data-src="{{ asset('storage/' . $file) }}"
                                         data-type="image">

                                @elseif($ext === 'pdf')
                                    <a href="#"
                                       class="preview-trigger text-decoration-none"
                                       data-nama="{{ $nama }}"
                                       data-src="{{ asset('storage/' . $file) }}"
                                       data-type="pdf">
                                        <i class="bi bi-file-earmark-pdf"></i> Lihat PDF
                                    </a>
                                @else
                                    <span class="text-muted">File tidak didukung</span>
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

    <a href="{{ route($prefix . '.dokumen-siswa.index') }}" class="btn btn-secondary mt-2">Kembali</a>
</div>

<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:#007efd; color:white;">
                <h5 class="modal-title">Preview Dokumen</h5>
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

    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    const docName = document.getElementById('docName');
    const docPreview = document.getElementById('docPreview');

    document.querySelectorAll('.preview-trigger').forEach(el => {
        el.addEventListener('click', function (e) {
            e.preventDefault();

            const nama = this.dataset.nama;
            const src  = this.dataset.src;
            const type = this.dataset.type;

            docName.textContent = nama;
            docPreview.innerHTML = '';

            if (type === 'image') {
                docPreview.innerHTML = `
                    <img src="${src}" class="img-fluid rounded shadow">
                `;
            } else {
                docPreview.innerHTML = `
                    <iframe src="${src}" width="100%" height="500" style="border:none;"></iframe>
                `;
            }

            modal.show();
        });
    });

});
</script>

@endsection
