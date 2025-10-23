@extends('layouts.app')

@section('content')
<div class="container">
    <h4>{{ $ptk->nama_lengkap }}</h4>

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
                    @php
                        $file = $dokumen->$field ?? null;
                    @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $nama }}</td>
                        <td>
                            @if($file)
                                @if(Str::endsWith($file, ['.jpg', '.jpeg', '.png', '.gif']))
                                    <img src="{{ asset('storage/' . $file) }}" alt="{{ $nama }}" style="max-width:100px; max-height:100px;">
                                @elseif(Str::endsWith($file, ['.pdf']))
                                    <a href="{{ asset('storage/' . $file) }}" target="_blank">Lihat PDF</a>
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

    <a href="{{ route('dokumen-ptk.index') }}" class="btn btn-secondary mt-2">Kembali</a>
</div>
@endsection
