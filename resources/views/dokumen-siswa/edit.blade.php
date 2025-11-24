@extends('layouts.app')

@section('content')
<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow-y: auto;
    }

    .container-fullheight {
        min-height: 100%;
        padding-bottom: 20px;
        max-width: 1100px;
        margin: 0 auto;
    }

    label {
        font-size: 0.9rem;
    }

    .form-control {
        font-size: 0.9rem;
        padding: 0.4rem 0.6rem;
    }

    img.preview {
        max-width: 80px;
        max-height: 80px;
        display: block;
        margin-bottom: 5px;
    }
</style>

<div class="container container-fullheight">

    <h3>{{ $dokumen->id ? 'Edit Dokumen Siswa' : 'Tambah Dokumen Siswa' }}</h3>

    @php
        $prefix = auth()->user()->role === 'admin' ? 'admin' : 'siswa';
    @endphp

    <form action="{{ $dokumen->id
            ? route($prefix . '.dokumen-siswa.update', $dokumen->peserta_didik_id)
            : route($prefix . '.dokumen-siswa.store') }}"
        method="POST" enctype="multipart/form-data">

        @csrf
        @if($dokumen->id)
            @method('PUT')
        @endif

        <input type="hidden" name="peserta_didik_id" value="{{ $dokumen->peserta_didik_id }}">

        <div class="row">
            <div class="col-md-6">
                @php
                    $dokumenListLeft = [
                        'akte_kelahiran' => 'Akte Kelahiran',
                        'kartu_keluarga' => 'Kartu Keluarga',
                        'ktp_ayah' => 'KTP Ayah',
                    ];
                @endphp

                @foreach($dokumenListLeft as $field => $nama)
                    @php $file = $dokumen->$field ?? null; @endphp
                    <div class="mb-2">
                        <label>{{ $nama }}</label>

                        @if($file)
                        <div>
                            @if(Str::endsWith($file, ['.jpg', '.jpeg', '.png', '.gif']))
                                <img src="{{ asset('storage/' . $file) }}" alt="{{ $nama }}" class="preview">
                            @elseif(Str::endsWith($file, ['.pdf']))
                                <a href="{{ asset('storage/' . $file) }}" target="_blank">Lihat PDF</a>
                            @else
                                <a href="{{ asset('storage/' . $file) }}" target="_blank">Lihat File</a>
                            @endif
                        </div>
                        @endif

                        <input type="file" name="{{ $field }}" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                @endforeach
            </div>

            <div class="col-md-6">
                @php
                    $dokumenListRight = [
                        'ktp_ibu' => 'KTP Ibu',
                        'ijazah_sd' => 'Ijazah SD',
                        'ijazah_smp' => 'Ijazah SMP',
                    ];
                @endphp

                @foreach($dokumenListRight as $field => $nama)
                    @php $file = $dokumen->$field ?? null; @endphp
                    <div class="mb-2">
                        <label>{{ $nama }}</label>

                        @if($file)
                        <div>
                            @if(Str::endsWith($file, ['.jpg', '.jpeg', '.png', '.gif']))
                                <img src="{{ asset('storage/' . $file) }}" alt="{{ $nama }}" class="preview">
                            @elseif(Str::endsWith($file, ['.pdf']))
                                <a href="{{ asset('storage/' . $file) }}" target="_blank">Lihat PDF</a>
                            @else
                                <a href="{{ asset('storage/' . $file) }}" target="_blank">Lihat File</a>
                            @endif
                        </div>
                        @endif

                        <input type="file" name="{{ $field }}" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                @endforeach
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix . '.dokumen-siswa.index') }}" class="btn btn-secondary me-2 btn-sm">Kembali</a>
            <button type="submit" class="btn btn-success btn-sm">Simpan</button>
        </div>

    </form>
</div>

@endsection
