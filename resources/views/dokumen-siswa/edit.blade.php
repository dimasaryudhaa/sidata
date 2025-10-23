@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Dokumen Siswa</h1>

    <form action="{{ route('dokumen-siswa.update', $dokumen->peserta_didik_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                    <div class="mb-3">
                        <label>{{ $nama }}</label>
                        @if($file)
                            <div class="mb-2">
                                @if(Str::endsWith($file, ['.jpg', '.jpeg', '.png', '.gif']))
                                    <img src="{{ asset('storage/' . $file) }}" alt="{{ $nama }}" style="max-width:100px; max-height:100px;">
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
                    <div class="mb-3">
                        <label>{{ $nama }}</label>
                        @if($file)
                            <div class="mb-2">
                                @if(Str::endsWith($file, ['.jpg', '.jpeg', '.png', '.gif']))
                                    <img src="{{ asset('storage/' . $file) }}" alt="{{ $nama }}" style="max-width:100px; max-height:100px;">
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
            <a href="{{ route('dokumen-siswa.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
